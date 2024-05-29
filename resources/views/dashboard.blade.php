@extends('layout.master') @push('plugin-styles')
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" /> @endpush @section('content') <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Welcome to Analytics TRUEST</h4>
    </div>
</div>
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-6 grid-margin stretch-card">
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
    <!-- row --> @endsection @push('plugin-scripts') <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script> @endpush @push('custom-scripts') <script src="{{ asset('assets/js/dashboard.js') }}"></script> @endpush