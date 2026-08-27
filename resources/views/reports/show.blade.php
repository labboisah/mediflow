@extends('layouts.app')

@section('content')

@php 
$reportData = Auth::user()->generateReportData($date, $fromDate ?? null, $toDate ?? null); 
dd($reportData);   
@endphp
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('reports.partials.report-actions')
            @include('reports.partials.report-header')

            {{-- Admin-Specific Report Content --}}
            @if(Auth::user()->hasRole('admin'))
                @include('reports.partials.admin_report', ['reportData' => $reportData])
            @endif
    
            {{-- Doctor-Specific Report Content --}}
            @if(Auth::user()->hasRole('doctor'))
                @include('reports.partials.doctor_report', ['reportData' => $reportData])
            @endif

            {{-- Nurse-Specific Report Content --}}
            @if(Auth::user()->hasRole('nurse'))
                @include('reports.partials.nurse_report', ['reportData' => $reportData])
            @endif

            {{-- Pharmacist-Specific Report Content --}}
            @if(Auth::user()->hasRole('pharmacist'))
                @include('reports.partials.pharmacist_report', ['reportData' => $reportData])
            @endif

            {{-- Midwife-Specific Report Content --}}
            @if(Auth::user()->hasRole('midwife'))
                @include('reports.partials.midwife_report', ['reportData' => $reportData])
            @endif

            {{-- Lab-Specific Report Content --}}
            @if(Auth::user()->hasRole('lab'))
                @include('reports.partials.lab_report', ['reportData' => $reportData])
            @endif

            {{-- Pharmacy-Specific Report Content --}}
            @if(Auth::user()->hasRole('pharmacy'))
                @include('reports.partials.pharmacy_report', ['reportData' => $reportData])
            @endif

            {{-- Radiology-Specific Report Content --}}
            @if(Auth::user()->hasRole('radiology'))
                @include('reports.partials.radiology_report', ['reportData' => $reportData])
            @endif

            {{-- Record Officer-Specific Report Content --}}
            @if(Auth::user()->hasRole('record'))
                @include('reports.partials.record_report', ['reportData' => $reportData])
            @endif

            {{-- Accountant-Specific Report Content --}}
            @if(Auth::user()->hasRole('accountant'))
                @include('reports.partials.accountant_report', ['reportData' => $reportData])
            @endif

             
        </div>
    </div>
</div>
@endsection