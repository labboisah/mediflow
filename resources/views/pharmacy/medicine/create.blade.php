@extends('layouts.app')

@section('content')

<div class="container">

<h4 class="mb-4">
<i class="bi bi-capsule"></i> Add Medicine
</h4>

<form method="POST" action="{{ route('pharmacy.medicines.store') }}">

@csrf

<div class="row">

<div class="col-md-6">

<div class="mb-3">
<label>Medicine Type</label>
<select name="medicine_type_id" class="form-control" required>
<option value="">Select medicine type</option>
@foreach($types as $type)
<option value="{{ $type->id }}" @selected(old('medicine_type_id') == $type->id)>{{ $type->name }}</option>
@endforeach
</select>
@error('medicine_type_id')<small class="text-danger">{{ $message }}</small>@enderror
</div>

</div>

<div class="col-md-6">

<div class="mb-3">
<label>Name</label>
<input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
@error('name')<small class="text-danger">{{ $message }}</small>@enderror
</div>

</div>

<div class="col-md-6">

<div class="mb-3">
<label>Generic Name</label>
<input type="text" name="generic_name" class="form-control" value="{{ old('generic_name') }}">
</div>

</div>

<div class="col-md-4">

<div class="mb-3">
<label>Form</label>
<select name="form" class="form-control">
@foreach(['Tablet', 'Capsule', 'Syrup', 'Injection', 'Cream'] as $form)
<option value="{{ $form }}" @selected(old('form') === $form)>{{ $form }}</option>
@endforeach
</select>
</div>

</div>

<div class="col-md-4">

<div class="mb-3">
<label>Strength</label>
<input type="text" name="strength" class="form-control" value="{{ old('strength') }}">
</div>

</div>

<div class="col-md-4">

<div class="mb-3">
<label>Manufacturer</label>
<input type="text" name="manufacturer" class="form-control" value="{{ old('manufacturer') }}">
</div>

</div>

</div>

<button class="btn btn-success">
<i class="bi bi-check-circle"></i> Save
</button>

</form>

</div>

@endsection
