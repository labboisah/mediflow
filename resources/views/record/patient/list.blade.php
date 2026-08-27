@extends('layouts.app')

@section('title', 'Patient List')

@section('content')
    <livewire:patient.patient-management mode="record" />
@endsection
