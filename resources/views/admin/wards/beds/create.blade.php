@extends('layouts.app')

@section('content')

<div class="container">

<div class="d-flex justify-content-between mb-3">

<h4>
<i class="bi bi-box-arrow-in-down"></i>
Add Bed
</h4>

<a href="{{ route('admin.beds.index', $ward) }}" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> Back
</a>

</div>



<div class="row">
    <div class="col-md-6 offset-3">
        <div class="card-body shadow-sm p-4">
            <form method="POST" action="{{ route('admin.beds.store',$ward) }}">
            @csrf
                <div class="form-group mb-4">
                    <label class="form-label">Bed No</label>
                    <input type="text" name="bed_no" value="{{old('bed_no')}}" class="form-control" placeholder="Enter Bed No" required>
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