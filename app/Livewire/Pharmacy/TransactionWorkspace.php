<?php

namespace App\Livewire\Pharmacy;

use App\Models\Bill;
use App\Models\MedicineBatch;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PharmacyDispense;
use App\Models\StockTransaction;
use App\Models\StockTransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class TransactionWorkspace extends Component
{
    public string $search = '';
    public string $batchId = '';
    public int|string $quantity = 1;
    public string $paymentMethodId = '';
    public string $referenceNumber = '';
    public array $cart = [];
    public ?int $receiptTransactionId = null;

    public function mount(): void
    {
        $this->paymentMethodId = (string) (PaymentMethod::where('is_active', true)->orderBy('name')->value('id') ?? '');
    }

    public function render()
    {
        return view('components.pharmacy.transaction-workspace', [
            'batches' => $this->availableBatches(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->orderBy('name')->get(),
            'total' => $this->total(),
            'receiptTransaction' => $this->receiptTransaction(),
        ]);
    }

    public function addToCart(): void
    {
        $validated = $this->validate([
            'batchId' => ['required', 'integer', 'exists:medicine_batches,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $batch = MedicineBatch::with('medicine')->findOrFail($validated['batchId']);
        $quantity = (int) $validated['quantity'];

        if ($batch->quantity_remaining < $quantity) {
            $this->feedback("Only {$batch->quantity_remaining} available for {$batch->medicine?->name}.", 'warning');
            return;
        }

        $existingIndex = collect($this->cart)->search(fn ($item) => (int) $item['batch_id'] === $batch->id);

        if ($existingIndex !== false) {
            $newQuantity = (int) $this->cart[$existingIndex]['quantity'] + $quantity;

            if ($newQuantity > $batch->quantity_remaining) {
                $this->feedback("Only {$batch->quantity_remaining} available for {$batch->medicine?->name}.", 'warning');
                return;
            }

            $this->cart[$existingIndex]['quantity'] = $newQuantity;
            $this->cart[$existingIndex]['subtotal'] = round($newQuantity * (float) $batch->selling_price, 2);
        } else {
            $this->cart[] = [
                'batch_id' => $batch->id,
                'medicine' => $batch->medicine?->name ?? 'N/A',
                'batch_number' => $batch->batch_number,
                'available' => (int) $batch->quantity_remaining,
                'price' => (float) $batch->selling_price,
                'quantity' => $quantity,
                'subtotal' => round($quantity * (float) $batch->selling_price, 2),
            ];
        }

        $this->reset(['batchId']);
        $this->quantity = 1;
        $this->feedback('Medicine added to cart.');
    }

    public function addBatchToCart(int $batchId): void
    {
        $this->batchId = (string) $batchId;
        $this->addToCart();
    }

    public function removeFromCart(int $index): void
    {
        if (! isset($this->cart[$index])) {
            return;
        }

        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->feedback('Medicine removed from cart.', 'warning');
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->receiptTransactionId = null;
        $this->feedback('Cart cleared.', 'warning');
    }

    public function completeTransaction(): void
    {
        $validated = $this->validate([
            'paymentMethodId' => ['required', 'integer', 'exists:payment_methods,id'],
            'referenceNumber' => ['nullable', 'string', 'max:255'],
        ]);

        if (empty($this->cart)) {
            $this->feedback('Add at least one medicine to the cart.', 'warning');
            return;
        }

        $transaction = DB::transaction(function () use ($validated) {
            $transaction = StockTransaction::create([
                'total_amount' => 0,
                'type' => 'dispense',
                'created_by' => auth()->id(),
            ]);

            $totalAmount = 0;
            $medicineNames = [];

            foreach ($this->cart as $item) {
                $batch = MedicineBatch::with('medicine')->whereKey($item['batch_id'])->lockForUpdate()->firstOrFail();
                $quantity = (int) $item['quantity'];
                $price = (float) $batch->selling_price;
                $subtotal = round($price * $quantity, 2);

                if ($quantity < 1 || $batch->quantity_remaining < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => "Insufficient stock for {$batch->medicine?->name}.",
                    ]);
                }

                $totalAmount += $subtotal;
                $medicineNames[] = "{$batch->medicine?->name} x {$quantity}";

                StockTransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'medicine_batch_id' => $batch->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
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
                'payment_method_id' => (int) $validated['paymentMethodId'],
                'reference_number' => $validated['referenceNumber'] ?: null,
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

            return $transaction;
        });

        $this->cart = [];
        $this->referenceNumber = '';
        $this->receiptTransactionId = $transaction->id;
        $this->feedback('Transaction completed. Receipt is ready.');
        $this->dispatch('print-pharmacy-thermal');
    }

    private function availableBatches()
    {
        return MedicineBatch::with('medicine')
            ->where('quantity_remaining', '>', 0)
            ->whereDate('expiry_date', '>=', today())
            ->when($this->search !== '', function ($query) {
                $search = trim($this->search);
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('batch_number', 'like', "%{$search}%")
                        ->orWhereHas('medicine', fn ($medicineQuery) => $medicineQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('generic_name', 'like', "%{$search}%")
                            ->orWhere('manufacturer', 'like', "%{$search}%"));
                });
            })
            ->orderBy('expiry_date')
            ->limit(30)
            ->get();
    }

    private function total(): float
    {
        return collect($this->cart)->sum(fn ($item) => (float) $item['subtotal']);
    }

    private function receiptTransaction(): ?StockTransaction
    {
        return $this->receiptTransactionId
            ? StockTransaction::with(['stockTransactionItems.medicineBatch.medicine', 'bill', 'payment.paymentMethod', 'createdBy'])->find($this->receiptTransactionId)
            : null;
    }

    private function feedback(string $message, string $type = 'success'): void
    {
        $this->dispatch('toast', message: $message, type: $type);
    }
}
