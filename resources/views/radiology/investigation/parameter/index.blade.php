@extends('layouts.app')

@section('title', 'Investigations')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clipboard2-data me-2 text-primary"></i>
        Manage Investigation Parameters
    </h1>
    <a href="{{ route('radiology.investigations.parameters.create',$investigation) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>
        New Parameter
    </a>
</div>
@endsection

@section('content')
    <div class="container">
       
        <h5 class="text-muted">{{$investigation->name}} Result Parameter</h5>
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Reference Range</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach ($investigation->parameters as $parameter)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $parameter->name }}</td>
                        <td>{{ $parameter->unit }}</td>
                        <td>{{ $parameter->reference_range }}</td>
                        <td class="text-end">
                           
                            <a href="{{ route('radiology.investigations.parameters.edit', [$investigation, $parameter]) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit   
                            </a>
                            <form action="{{ route('radiology.investigations.parameters.destroy', [$investigation, $parameter]) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this investigation?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        
                        </td>
                    </tr>
               
                @endforeach
                
            </tbody>
        </table>
    </div>
@endsection