@extends('layout.master') @push('plugin-styles')
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" /> 
@endpush 

@php
    // Mendapatkan segmen-segmen dari URL saat ini
    $segments = request()->segments();
@endphp

@section('content') 
<div class="row">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
            @foreach($segments as $key => $segment)
                @if($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $segment }}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ implode('/', array_slice($segments, 0, $key + 1)) }}">{{ $segment }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">Payrol </h6>
                        </div>
                        <div class="">
                            <div class="col-12 col-md-12 col-xl-5 w-100">
                                <h3 class="mb-2">Rp {{ number_format($totalPeriode, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                        <p>Total Payrol Periode Sekarang</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">Payrol Management</h6>
                        </div>
                        <div class="">
                            <div class="col-12 col-md-12 col-xl-5 w-100">
                                <h3 class="mb-2">Rp {{ number_format($ManagementData, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                        <p>Total Payrol Management Periode Sekarang</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">Payrol Frontline</h6>
                        </div>
                        <div class="">
                            <div class="col-12 col-md-12 col-xl-5 w-100">
                                <h3 class="mb-2">Rp {{ number_format($FrontlineData, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                        <p>Total Payrol Frontline Periode Sekarang</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <p>Payrol Berdasarkan Periode Tahun Ini</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <canvas id="salaryChart"></canvas>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <p>Payrol Berdasarkan Tahun</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <canvas id="salaryChartYears"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
@endsection 
@push('plugin-scripts') 
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/chartjs/chart.umd.js') }}"></script>
@endpush
@push('custom-scripts') 
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/chartjs.js') }}"></script>
<style>
    .text-center {
        text-align: center;
    }
</style>
<script>
        var ctx = document.getElementById('salaryChart').getContext('2d');
        var salaryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($managementDataPeriode->keys()) !!},
                datasets: [{
                    label: 'Management Leaders',
                    data: {!! json_encode($managementDataPeriode->values()) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Frontline',
                    data: {!! json_encode($frontlineDataPeriode->values()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var ctx = document.getElementById('salaryChartYears').getContext('2d');
        var salaryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($managementDataYearly->keys()) !!},
                datasets: [{
                    label: 'Management Leaders',
                    data: {!! json_encode($managementDataYearly->values()) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Frontline',
                    data: {!! json_encode($frontlineDataYearly->values()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endpush