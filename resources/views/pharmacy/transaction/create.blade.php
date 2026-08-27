@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h4 class="mb-4">
    <i class="bi bi-receipt"></i> Pharmacy Transaction
    </h4>

<div class="row">

<div class="col-md-4">

<div class="card shadow-sm">

    <div class="card-header">
    Add Medicine
    </div>

    <div class="card-body">
    <form action="{{route('pharmacy.transactions.store')}}" method="post">   
        @csrf 
        <input type="hidden" name="items" id="itemsInput">
        
        <input type="hidden" id="total_amount" name="total_amount" value="0">

        <div class="mb-3">
            <label>Payment Method</label>
            <select name="payment_method_id" class="form-control @error('payment_method_id') is-invalid @enderror" required>
                <option value="">Select Payment Method</option>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->id }}" @selected(old('payment_method_id') == $method->id)>{{ $method->name }}</option>
                @endforeach
            </select>
            @error('payment_method_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label>Payment Reference</label>
            <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}" placeholder="Optional receipt/POS/transfer reference">
        </div>
        
        <div class="mb-3">
        <label>Medicine</label>

        <select id="medicineSelect" class="form-control">
        <option value="">Select Medicine</option>

        @foreach($batches as $batch)

        <option
        value="{{ $batch->id }}"
        data-price="{{ $batch->selling_price }}"
        data-name="{{ $batch->medicine->name }}"
        data-stock="{{ $batch->quantity_remaining }}">

        {{ $batch->medicine->name }}
        (Stock: {{ $batch->quantity_remaining }}, Price: {{ $batch->selling_price }})
        </option>

        @endforeach

        </select>
        </div>

            <div class="mb-3">
                <label>Quantity</label>
                <input type="number" id="quantityInput" class="form-control" min="1">
            </div>

            
            <a class="btn btn-success w-10" id="addMedicineBtn">
            <i class="bi bi-plus-circle"></i> Add
            </a>
            
        </div>

    </div>

</div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">
            Transaction Summary
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="transactionTable">

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">
                            Total
                            </td>
                            <td colspan="2">
                            ₦ <span id="totalAmount">0</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <button class="btn btn-danger w-90" id="submitTransactionBtn" type="submit">
                    <i class="bi bi-check-circle"></i> Proceed Payment
                </button>
            </div>

        </div>
</form>
    </div>

</div>

</div>

<script>

let transactionItems = [];
let totalAmount = 0;

const medicineSelect = document.getElementById('medicineSelect');
const quantityInput = document.getElementById('quantityInput');
const tableBody = document.getElementById('transactionTable');
const totalDisplay = document.getElementById('totalAmount');

document.getElementById('addMedicineBtn')
.addEventListener('click', function(){

    const selected = medicineSelect.options[medicineSelect.selectedIndex];

    const batchId = selected.value;
    const name = selected.dataset.name;
    const price = parseFloat(selected.dataset.price);
    const quantity = parseInt(quantityInput.value);
    const stock = parseInt(selected.dataset.stock);

    if(!batchId || !quantity) return;
    if(quantity > stock) {
        alert(`Only ${stock} available for ${name}.`);
        return;
    }

    const subtotal = price * quantity;

    transactionItems.push({
        batchId,
        name,
        price,
        quantity,
        subtotal
    });

    renderTable();
    document.getElementById('itemsInput').value = JSON.stringify(transactionItems);       
           

});

function renderTable(){

    tableBody.innerHTML = '';

    totalAmount = 0;

    transactionItems.forEach((item,index)=>{

        totalAmount += item.subtotal;

        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.price}</td>
            <td>${item.quantity}</td>
            <td>${item.subtotal}</td>
            <td>
                <button class="btn btn-danger btn-sm"
                onclick="removeItem(${index})">
                <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(row);

    });

    totalDisplay.innerText = totalAmount;
    document.getElementById('total_amount').value = totalAmount;

}

function removeItem(index){

    transactionItems.splice(index,1);

    renderTable();
    document.getElementById('itemsInput').value =
        JSON.stringify(transactionItems);

}

document.querySelector('form').addEventListener('submit',function(){

    document.getElementById('itemsInput').value =
        JSON.stringify(transactionItems);

});

</script>
@endsection
