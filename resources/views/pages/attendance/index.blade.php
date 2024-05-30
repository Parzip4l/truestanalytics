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
                            <h6 class="card-title mb-2">Karyawan</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$employeeCount}}</h3>
                            </div>
                        </div>
                        <p>Total Karyawan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">Kehadiran</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$hadir['jumlah']}}</h3>
                            </div>
                        </div>
                        <p>Karyawan Absen Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">Ketidakhadiran</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$absen['jumlah']}}</h3>
                            </div>
                        </div>
                        <p>Karyawan Tidak Absen Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <p>Persentase Kehadiran Karyawan Hari Ini</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <canvas id="attendanceChart"></canvas>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <p>Statistik Kehadiran Periode Bulan Ini</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <canvas id="attendanceChart2"></canvas>
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
        var ctx = document.getElementById('attendanceChart').getContext('2d');
        var chartData = @json($chartData);
        var attendanceChart = new Chart(ctx, {
            type: 'pie',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                            }
                        }
                    }
                }
            },
        });
    </script>
    <script>
        // Ambil data absensi dari blade dan konversi menjadi objek JavaScript
        var data = {!! json_encode($AbsenByday) !!};

        // Siapkan canvas untuk chart
        var ctx = document.getElementById('attendanceChart2').getContext('2d');

        // Buat chart
        var attendanceChart2 = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Tanggal'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Jumlah'
                        }
                    }]
                }
            }
        });
    </script>
@endpush