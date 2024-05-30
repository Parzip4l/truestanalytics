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
                            <h6 class="card-title mb-2">Laki Laki</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$lakilaki}}</h3>
                            </div>
                        </div>
                        <p>Total Laki Laki</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">Perempuan</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$perempuan}}</h3>
                            </div>
                        </div>
                        <p>Total Perempuan</p>
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
                            <p>Persentase Karyawan Berdasarkan Jenis Kelamin</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <canvas id="persentaseGender"></canvas>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <p>Jumlah Karyawan Berdasarkan Rentang Usia</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <canvas id="ageDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <p>Jumlah Karyawan Berdasarkan Agama</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <canvas id="AgamaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <p>Jumlah Karyawan Berdasarkan Tingkat Pendidikan</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <canvas id="ChartPendidikan"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <p>Jumlah Karyawan Berdasarkan Lama Bekerja</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <canvas id="ChartKerja"></canvas>
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
    var ctx = document.getElementById('persentaseGender').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Laki-Laki', 'Perempuan'],
            datasets: [{
                label: 'Persentase Gender',
                data: [{{$lakilakiPercentage}}, {{$perempuanPercentage}}],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var percentage = Math.round(((currentValue / total) * 100));
                        return percentage + '%';
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
</script>
<script>
    // Ambil data dari PHP dan inisialisasi chart
    var ageGroups = @json($ageGroups);
    var ctx = document.getElementById('ageDistributionChart').getContext('2d');
    var ageDistributionChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(ageGroups),
            datasets: [{
                label: 'Jumlah Karyawan',
                data: Object.values(ageGroups),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
</script>
<script>
    // Ambil data dari PHP dan inisialisasi chart
    var educationLevels = @json($educationLevels);
    var ctx = document.getElementById('ChartPendidikan').getContext('2d');
    var ChartPendidikan = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(educationLevels),
            datasets: [{
                label: 'Jumlah Karyawan',
                data: Object.values(educationLevels),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(23, 32, 42, 0.2)',
                    'rgba(255, 193, 7, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(23, 32, 42, 1)',
                    'rgba(255, 193, 7, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
    // Ambil data dari PHP dan inisialisasi chart
    var agama = @json($agama);
    var ctx = document.getElementById('AgamaChart').getContext('2d');
    var ChartPendidikan = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(agama),
            datasets: [{
                label: 'Jumlah Karyawan',
                data: Object.values(agama),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
<script>
    // Ambil data dari PHP dan inisialisasi chart
    var WorkingLength = @json($WorkingLength);
    var ctx = document.getElementById('ChartKerja').getContext('2d');
    var ChartKerja = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(WorkingLength),
            datasets: [{
                label: 'Jumlah Karyawan',
                data: Object.values(WorkingLength),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                    
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush