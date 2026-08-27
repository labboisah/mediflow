@extends('layouts.app')

@section('content')

<div class="container">

<div class="d-flex justify-content-between mb-3">

<h4>
<i class="bi bi-box-arrow-in-down"></i>
Add Ward
</h4>

<a href="{{ route('admin.wards.index') }}" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> Back
</a>

</div>



<div class="row">
    <div class="col-md-6 offset-3">
        <div class="card-body shadow-sm p-4">
            <form method="POST" action="{{ route('admin.wards.store') }}">
            @csrf
                <div class="form-group mb-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Ward Name" required>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Price</label>
                    <input type="text" name="price" class="form-control" placeholder="Enter Price per day" required>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" placeholder="Enter Bed Capacity" required>
                </div>
        
                <div class="form-group mb-4">
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle"></i>
                        Register
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


@endsection