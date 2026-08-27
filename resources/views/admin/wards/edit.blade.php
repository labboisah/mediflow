@extends('layouts.app')

@section('content')

<div class="container">

<div class="d-flex justify-content-between mb-3">

<h4>
<i class="bi bi-box-arrow-in-down"></i>
Edit Ward
</h4>

<a href="{{ route('admin.wards.index') }}" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> Back
</a>

</div>

<div class="row">
    <div class="col-md-6 offset-3">
        <div class="card-body shadow-sm p-4">
            <form method="POST" action="{{ route('admin.wards.update',$ward) }}">
            @csrf
            @method('PUT')
                <div class="form-group mb-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{$ward->name}}" placeholder="Enter ward Name" required>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Price</label>
                    <input type="text" name="price" class="form-control" value="{{$ward->price}}" placeholder="Enter Price per day" required>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" value="{{$ward->capacity}}" class="form-control" placeholder="Enter Bed Capacity" required>
                </div>
        
                <div class="form-group mb-4">
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle"></i>
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


@endsection