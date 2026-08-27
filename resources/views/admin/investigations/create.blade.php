@extends('layouts.app')

@section('content')

<div class="container">

<div class="d-flex justify-content-between mb-3">

<h4>
<i class="bi bi-box-arrow-in-down"></i>
Add Investigation
</h4>

<a href="{{ route('admin.investigations.index') }}" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> Back
</a>

</div>



<div class="row">
    <div class="col-md-6 offset-3">
        <div class="card-body shadow-sm p-4">
            <form method="POST" action="{{ route('admin.investigations.store') }}">
            @csrf
                <div class="form-group mb-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="Enter Investigation Name" required>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Price</label>
                    <input type="text" name="price" class="form-control" value="{{old('price')}}" placeholder="Enter investigation Name" required>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Investigation Type</label>
                    <select name="investigation_type" class="form-control">
                        <option value=""></option>
                        @foreach(App\Models\InvestigationType::all() as $type)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
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