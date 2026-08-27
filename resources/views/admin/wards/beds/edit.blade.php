@extends('layouts.app')

@section('content')

<div class="container">

<div class="d-flex justify-content-between mb-3">

<h4>
<i class="bi bi-box-arrow-in-down"></i>
Edit Bed {{$bed->bed_no}}
</h4>

<a href="{{ route('admin.beds.index',$bed->ward) }}" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> Back
</a>

</div>



<div class="row">
    <div class="col-md-6 offset-3">
        <div class="card-body shadow-sm p-4">
            <form method="POST" action="{{ route('admin.beds.update',$bed) }}">
            @csrf
            @method('PUT')
                <div class="form-group mb-4">
                    <label class="form-label">Bed No</label>
                    <input type="text" name="bed_no" class="form-control" value="{{$bed->bed_no}}" placeholder="Enter Department Name" required>
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