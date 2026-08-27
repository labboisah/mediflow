@extends('layouts.app')

@section('content')

<div class="container">

<h4>
<i class="bi bi-box-seam"></i> Receive Stock
</h4>

<form method="POST" action="{{ route('pharmacy.stocks.store') }}">

@csrf

<div class="row">

<div class="col-md-6">

<label>Medicine</label>
<select name="medicine_id" class="form-control">

@foreach($medicines as $medicine)
<option value="{{ $medicine->id }}">
{{ $medicine->name }}
</option>
@endforeach

</select>

</div>

<div class="col-md-6">

<label>Batch Number <small class="text-muted">(optional)</small></label>
<input type="text" name="batch_number" class="form-control">

</div>

<div class="col-md-4">

<label>Quantity</label>
<input type="number" name="quantity_received" class="form-control">

</div>

<div class="col-md-4">

<label>Purchase Price</label>
<input type="number" step="0.01" name="purchase_price" class="form-control">

</div>

<div class="col-md-4">

<label>Selling Price</label>
<input type="number" step="0.01" name="selling_price" class="form-control">

</div>

<div class="col-md-6">

<label>Manufacture Date <small class="text-muted">(optional)</small></label>
<input type="date" name="manufacture_date" class="form-control">

</div>

<div class="col-md-6">

<label>Expiry Date <small class="text-muted">(optional)</small></label>
<input type="date" name="expiry_date" class="form-control">

</div>

</div>

<br>

<button class="btn btn-success">
<i class="bi bi-check-circle"></i> Save Batch
</button>

</form>

</div>

@endsection
