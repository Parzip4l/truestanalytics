@extends('layout.master') @push('plugin-styles')
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" /> @endpush @section('content') <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Welcome to Analytics TRUEST</h4>
    </div>
</div>
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-6 col-sm-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-0">Page Views</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$uniqueVisitorsCount['current_page_view_count']}}</h3>
                                <div class="d-flex align-items-baseline">
                                    @php
                                        $visitorCountChangePercentage = $uniqueVisitorsCount['page_view_count_change_percentage'];
                                        $arrowIcon = $visitorCountChangePercentage >= 0 ? 'arrow-up' : 'arrow-down';
                                        $textColorClass = $visitorCountChangePercentage >= 0 ? 'text-success' : 'text-danger';
                                    @endphp
                                    <p class="{{$textColorClass}}">
                                        <span>{{$visitorCountChangePercentage}}%</span>
                                        <i data-feather="{{$arrowIcon}}" class="icon-sm mb-1"></i>
                                    </p>
                                </div>
                            </div>
                            <div class="col-6 col-md-12 col-xl-7">
                                <div id="customersChart" class="mt-md-3 mt-xl-0"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-0">Visitor</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5">
                                <h3 class="mb-2">{{$uniqueVisitorsCount['current_unique_visitor_count']}}</h3>
                                <div class="d-flex align-items-baseline">
                                    @php
                                        $visitorCountChangePercentage = $uniqueVisitorsCount['visitor_count_change_percentage'];
                                        $arrowIcon = $visitorCountChangePercentage >= 0 ? 'arrow-up' : 'arrow-down';
                                        $textColorClass = $visitorCountChangePercentage >= 0 ? 'text-success' : 'text-danger';
                                    @endphp
                                    <p class="text-success">
                                        <span>{{$visitorCountChangePercentage}}%</span>
                                        <i data-feather="{{$arrowIcon}}" class="icon-sm mb-1"></i>
                                    </p>
                                </div>
                            </div>
                            <div class="col-6 col-md-12 col-xl-7">
                                <div id="ordersChart" class="mt-md-3 mt-xl-0"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-7 col-sm-12 grid-margin stretch-card">
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
            <div class="col-md-5 col-sm-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <p>Data Karyawan Dengan Tingkat Kehadiran Paling Tinggi Periode Bulan Ini</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Organisasi</th>
                                            <th>Total Kehadiran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userAktif as $data)
                                        <tr>
                                            <td>{{$data->nama}}</td>
                                            <td>{{$data->organisasi}}</td>
                                            <td>{{$data->total_absensi}}</td>
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

<!-- row --> 
@endsection
@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chartjs/chart.umd.js') }}"></script>
@endpush 
@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/chartjs.js') }}"></script>
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