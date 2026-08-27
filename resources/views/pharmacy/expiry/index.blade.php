@extends('layouts.app')

@section('content')

<div class="container-fluid">

<h4>
<i class="bi bi-exclamation-triangle text-danger"></i>
Expiring Medicines
</h4>

<div class="card shadow-sm">

<table class="table table-striped">

<thead>
<tr>
<th>Medicine</th>
<th>Batch</th>
<th>Quantity</th>
<th>Expiry Date</th>
</tr>
</thead>

<tbody>

@foreach($batches as $batch)

<tr>

<td>{{ $batch->medicine->name }}</td>
<td>{{ $batch->batch_number }}</td>
<td>{{ $batch->quantity_remaining }}</td>

<td>
<span class="badge bg-danger">
{{ $batch->expiry_date }}
</span>
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection