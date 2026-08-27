@extends('layouts.app')

@section('title', 'Investigations')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clipboard2-data me-2 text-primary"></i>
        Manage Investigations
    </h1>
    <a href="{{ route('lab.investigations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>
        New Investigation
    </a>
</div>
@endsection

@section('content')
    <div class="container">
        @foreach (auth()->user()->department->investigationTypes as $investigationType)
        <h5 class="text-muted">{{$investigationType->name}}</h5>
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Request</th>
                    <th>Result Parameters</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach ($investigationType->investigations as $investigation)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $investigation->code }}</td>
                        <td>{{ $investigation->name }}</td>
                        <td>{{ $investigation->price }}</td>
                        <td>{{$investigation->investigationRequests->count()}}</td>
                        <td>{{$investigation->parameters->count()}}</td>
                        <td class="text-end">
                            <a href="{{route('lab.investigations.parameters.index', $investigation)}}" class="btn btn-sm btn-success">
                                <i class="bi bi-eye"></i> View Parameters
                            </a>
                            <a href="{{ route('lab.investigations.edit', $investigation) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit   
                            </a>
                            <form action="{{ route('lab.investigations.destroy', $investigation) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this investigation?');">
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
        @endforeach
    </div>
@endsection