@extends('layouts.app')

@section('content')

<div class="container">

<div class="d-flex justify-content-between mb-3">

<h4>
<i class="bi bi-box-arrow-in-down"></i>
Edit Investigation
</h4>

<a href="{{ route('admin.investigations.index') }}" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> Back
</a>

</div>



<div class="row">
    <div class="col-md-6 offset-3">
        <div class="card-body shadow-sm p-4">
            <form method="POST" action="{{ route('admin.investigations.update',$investigation) }}">
            @csrf
            @method('PUT')
                <div class="form-group mb-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{$investigation->name}}" placeholder="Enter investigation Name" required>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Price</label>
                    <input type="text" name="price" class="form-control" value="{{$investigation->price}}" placeholder="Enter investigation Name" required>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Investigation Type</label>
                    <select name="investigation_type" class="form-control">
                        <option value="{{$investigation->investigationType->id}}">{{$investigation->investigationType->name}}</option>
                        @foreach($investigation->investigationType->department->investigationTypes as $type)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
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