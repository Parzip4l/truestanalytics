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
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">Tanpa Keterangan</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$tanpaKeterangan['jumlah']}}</h3>
                            </div>
                        </div>
                        <p>Total Tanpa Keterangan Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">Izin</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$dataIzin['jumlah']}}</h3>
                            </div>
                        </div>
                        <p>Karyawan Izin Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">Sakit</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$dataSakit['jumlah']}}</h3>
                            </div>
                        </div>
                        <p>Karyawan Sakit Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">WFE</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$dataWfe['jumlah']}}</h3>
                            </div>
                        </div>
                        <p>Karyawan WFE Hari Ini</p>
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
                            <p>Data Karyawan Izin Periode Bulan Ini</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Organisasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach($dataIzin['dataIzin'] as $data )
                                        <tr>
                                            <td>{{$data->nama}}</td>
                                            <td>{{$data->organisasi}}</td>
                                        </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <div class="text-center mb-3">
                            <p>Data Karyawan Sakit Bulan Ini</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Organisasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach($dataSakit['dataSakit'] as $data )
                                        <tr>
                                            <td>{{$data->nama}}</td>
                                            <td>{{$data->organisasi}}</td>
                                        </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <div class="text-center mb-3">
                            <p>Data Karyawan WFE Periode Bulan Ini</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Organisasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataWfe['dataWfh'] as $data )
                                        <tr>
                                            <td>{{$data->nama}}</td>
                                            <td>{{$data->organisasi}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table> 
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
@endpush