@extends('layouts.app')

@section('title', 'Data Synchronization Dashboard')

@section('content')
    <div class="container">
        <h1>Data Synchronization Dashboard</h1>
        <!-- Add your synchronization dashboard content here -->
         <table class="table table-bordered datatable" data-refresh="15000">
            <thead>
                <tr>
                    <th>Table</th>
                    <th>Total Records</th>
                    <th>Synchronized</th>
                    <th>Pending Sync</th>
                    <th>Failed Sync</th>
                    <th>Local Origin</th>
                    <th>Online Origin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(Auth::user()->getModels() as $model)
                    <tr>
                        <td>{{ $model['name'] }}</td>
                        <td>{{ $model['class']::count() }}</td>
                        <td>{{ $model['class']::where('sync_status', 'synced')->count() ?? 0 }}</td>
                        <td>{{ $model['class']::where('sync_status', 'pending')->count() ?? 0 }}</td>
                        <td>{{ $model['class']::where('sync_status', 'failed')->count() ?? 0 }}</td>
                        <td>{{ $model['class']::where('sync_origin', 'local')->count() ?? 0 }}</td>
                        <td>{{ $model['class']::where('sync_origin', 'online')->count() ?? 0 }}</td>
                        <td>
                            <button class="btn btn-primary">Synchronize</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
         </table>
    </div>
    @push('vite')
        @vite('resources/js/datatable.js')
    @endpush
@endsection
