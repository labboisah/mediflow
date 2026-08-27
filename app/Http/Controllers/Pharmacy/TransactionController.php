<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\MedicineBatch;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PharmacyDispense;
use App\Models\StockTransaction;
use App\Models\StockTransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index() {
        $transactions = StockTransaction::with(['stockTransactionItems.medicineBatch.medicine', 'createdBy', 'bill', 'payment.paymentMethod'])
            ->latest()
            ->get();

        return view('pharmacy.transaction.index', compact('transactions'));
    }

    public function report(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = $validated['from'] ?? today()->startOfMonth()->toDateString();
        $to = $validated['to'] ?? today()->toDateString();

        $transactions = StockTransaction::with(['stockTransactionItems.medicineBatch.medicine', 'createdBy'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->latest()
            ->get();

        return view('pharmacy.transaction.report', compact('transactions', 'from', 'to'));
    }

    public function create() {
        $batches = MedicineBatch::with('medicine')
            ->where('quantity_remaining', '>', 0)
            ->whereDate('expiry_date', '>=', today())
            ->latest()
            ->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('name')->get();

        return view('pharmacy.transaction.create', compact('batches', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_amount' => ['required', 'numeric', 'min:0.01'],
            'items' => ['required', 'json'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'reference_number' => ['nullable', 'string', 'max:255'],
        ]);

        $items = collect(json_decode($request->items, true))
            ->filter(fn ($item) => isset($item['batchId'], $item['quantity'], $item['price'], $item['subtotal']));

        if ($items->isEmpty()) {
            throw ValidationException::withMessages(['items' => 'Add at least one medicine to the transaction.']);
        }

        $payment = DB::transaction(function() use ($request, $items){
            $transaction = StockTransaction::create([
                'total_amount' => 0,
                'type' => 'dispense',
                'created_by' => auth()->id()
            ]);

            $totalAmount = 0;
            $medicineNames = [];

            foreach($items as $item){
                $batch = MedicineBatch::with('medicine')->whereKey($item['batchId'])->lockForUpdate()->firstOrFail();
                $quantity = (int) $item['quantity'];
                $price = (float) $batch->selling_price;
                $subtotal = round($price * $quantity, 2);

                if ($quantity < 1 || $batch->quantity_remaining < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Insufficient stock for {$batch->medicine?->name}.",
                    ]);
                }

                $totalAmount += $subtotal;
                $medicineNames[] = "{$batch->medicine?->name} x {$quantity}";

                StockTransactionItem::create([

                    'transaction_id' => $transaction->id,
                    'medicine_batch_id' => $batch->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal

                ]);

                PharmacyDispense::create([
                    'medicine_batch_id' => $batch->id,
                    'type' => 'dispense',
                    'quantity' => $quantity,
                    'reference' => $transaction->id,
                    'created_by' => auth()->id(),
                ]);

                $batch->decrement('quantity_remaining', $quantity);
            }

            $bill = Bill::create([
                'department_id' => auth()->user()?->department_id,
                'bill_number' => Bill::generateBillNumber(),
                'service_description' => 'Pharmacy transaction: ' . implode(', ', $medicineNames),
                'amount' => $totalAmount,
                'due_amount' => $totalAmount,
                'status' => 'pending',
                'issued_by' => auth()->id(),
                'issued_date' => now(),
                'due_date' => now(),
                'notes' => 'Generated from pharmacy transaction #' . $transaction->id,
            ]);

            $payment = Payment::create([
                'payment_id' => Payment::generatePaymentID(),
                'amount' => $totalAmount,
                'payment_method_id' => $request->payment_method_id,
                'reference_number' => $request->reference_number,
                'status' => 'completed',
                'notes' => 'Payment collected for pharmacy transaction #' . $transaction->id,
                'bill_id' => $bill->id,
                'paid_by' => auth()->id(),
                'payment_date' => now(),
            ]);

            $bill->update(['status' => 'paid']);

            $transaction->update([
                'total_amount' => $totalAmount,
                'reference' => $bill->bill_number,
                'bill_id' => $bill->id,
                'payment_id' => $payment->id,
            ]);

            return $payment;

        });
        return redirect()->route('pharmacy.finance.payments.receipt', $payment)->with('success', 'Transaction and payment registered');

    }

}
