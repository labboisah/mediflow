@extends('layouts.app')

@section('title', 'departments')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clipboard2-data me-2 text-warning"></i>
        Manage Investigation
    </h1>
    <div class="ms-auto d-flex">
        <a href="{{ route('admin.investigations.create') }}" class="btn btn-sm btn-success ms-3">
            <i class="bi bi-plus-circle me-1"></i>New Investigation
        </a>
    </div>
</div>
@endsection

@section('content')

@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="row">
    <div class="col-md-10 offset-1">
        @forelse ($departments as $department)
        @if($department->investigationTypes->count()> 0)
       
        <div class="card shadow-sm p-4">
            <h5>{{$department->name}} Investigations</h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Investigation</th>
                            <th>Price</th>
                            <th>Parameters</th>
                            <th class="no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($department->investigationTypes as $type)
                            <tr>
                                <td collspan='4'>{{$type->name}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @foreach($type->investigations as $investigation)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $investigation->name }}</td>
                                <td>{{ $investigation->price }}</td>
                                <td>{{ $investigation->parameters->count() }}</td>
                                
                                <td>
                                
                                    <a href="{{ route('admin.investigations.edit', $investigation) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.investigations.destroy', $investigation) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this Investigation?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                        
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endsection
