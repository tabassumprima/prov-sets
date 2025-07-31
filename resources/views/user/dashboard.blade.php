@extends('user.layouts.app')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <x-toast :errors="$errors" />

            <!-- Dashboard Ecommerce Starts -->
            <section id="dashboard-ecommerce">
                <!-- Filters starts-->
                <form id="dashboard">
                    <div class="row">
                        <div class="col-md-3 col-xl-3 col-lg-3 col-sm-12">
                            <div class="form-group custom-select-box">
                                <label for="accounting-year">Accounting year</label>
                                <select class="select2 form-control" id="accounting-year" name='accounting_year_id'>
                                    @foreach ($accountingYears as $accountingYear)
                                        <option value="{{CustomHelper::encode($accountingYear->id) }}">
                                            {{ $accountingYear->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xl-3 col-lg-3 col-sm-12">
                            <div class="form-group custom-select-box">
                                <label for="portfolio">Portfolios</label>
                                <select class="select2 custom-multiple form-control" id="portfolio" multiple="multiple"
                                    name='portfolio_id[]'>
                                    @foreach ($portfolios as $portfolio)
                                        <option value="{{CustomHelper::encode($portfolio->id) }}">
                                            {{ $portfolio->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-xl-2 col-lg-2 col-sm-12">
                            <div class="form-group custom-select-box">
                                <label for="branch">Branch</label>
                                <select class="select2 form-control" id="branch" name='branch_id'>
                                    <option value="All">All</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{CustomHelper::encode($branch->id) }}">
                                            {{ $branch->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-xl-2 col-lg-2 col-sm-12">
                            <div class="form-group custom-select-box">
                                <label for="business-type">Business type</label>
                                <select class="select2 form-control" id="business-type" name='business_type_id'>
                                    @foreach ($businessTypes as $businessType)
                                        <option value="{{CustomHelper::encode($businessType->id) }}">
                                            {{ $businessType->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-xl-2 col-lg-2 col-sm-12">
                            <div class="row filters-btn">
                                <div class="col-md-6 col-xl-6 col-lg-6 col-sm-6 pb-1">
                                    <button class="btn add-new btn-primary mr-1 w-100 d-md-block" type="button"
                                        id="filters">
                                        <span class="d-md-none">Filter</span>
                                        <i data-feather="filter" class="d-none d-md-inline"></i>
                                    </button>
                                </div>
                                <div class="col-md-6 col-xl-6 col-lg-6 col-sm-6 pb-1">
                                    <button class="btn add-new btn-primary w-100 d-md-block" type="button" id="reset">
                                        <span class="d-md-none">Reset</span>
                                        <i data-feather="refresh-ccw" class="d-none d-md-inline"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Filters ends-->
                <div class="row match-height">
                    <!--Current loss ratio card starts -->
                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="chart-wrapper loss-ratio-chart">
                                <div class="shimmer-wrapper">
                                    <div class="shimmer"></div>
                                </div>
                            </div>
                            <div class="card-header d-flex justify-content-between pb-0">
                                <h4 class="card-title">Current Loss Ratio - FY <span class="selected-date"></span></h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 col-12 d-flex justify-content-center">
                                        <div id="loss-ratio-chart"></div>
                                    </div>
                                </div>
                                <div class="mt-5">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4 offset-1 col-sm-6">
                                            <p class="card-text text-muted mb-0">Total claims</p>
                                            <h4 class="fw-bolder mb-0"><small class="font-weight-bolder">
                                                    {{$getOrgCurrency->currency->symbol}}
                                                </small> <span class="claims"></span></h4>
                                        </div>
                                        <div class="col-md-4 offset-3 col-sm-6">
                                            <p class="card-text text-muted mb-0">Total premiums</p>
                                            <h4 class="fw-bolder mb-0"><small class="font-weight-bolder">
                                                    {{$getOrgCurrency->currency->symbol}}
                                                </small> <span class="premium"></span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Current loss ratio card ends -->
                    <!-- Stats Card -->
                    <div class="col-lg-6 col-12">
                        <div class="card card-transaction stats-card">
                            <div class="chart-wrapper card-stats">
                                <div class="shimmer-wrapper">
                                    <div class="shimmer"></div>
                                </div>
                            </div>
                            <div class="card-header">
                                <h4 class="card-title">Performance Snapshot - FY <span
                                        class="selected-date"></span><br><span class="text-secondary pt-1">Underwriting
                                        result for the period</span></h4>

                            </div>
                            <div class="card-body mt-1">
                                <div class="transaction-item">
                                    <div class="media">
                                        <div class="avatar bg-light-primary rounded">
                                            <div class="avatar-content">
                                                <i data-feather="trending-up" class="avatar-icon font-medium-3"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="transaction-title">Net Premium</h4>
                                        </div>
                                    </div>
                                    <div class="font-weight-bolder pt-1">
                                        <h4>
                                            {{$getOrgCurrency->currency->symbol}} <span class="total-premium"></span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="transaction-item">
                                    <div class="media">
                                        <div class="avatar bg-light-success rounded">
                                            <div class="avatar-content">
                                                <i data-feather="trending-down" class="avatar-icon font-medium-3"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="transaction-title">Net Claims</h4>
                                        </div>
                                    </div>
                                    <div class="font-weight-bolder pt-1">
                                        <h4>
                                            {{$getOrgCurrency->currency->symbol}} <span class="total-claims"></span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="transaction-item">
                                    <div class="media">
                                        <div class="avatar bg-light-danger rounded">
                                            <div class="avatar-content">
                                                <i data-feather="trending-down" class="avatar-icon font-medium-3"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="transaction-title">Net Expenses</h4>
                                        </div>
                                    </div>
                                    <div class="font-weight-bolder pt-1">
                                        <h4>
                                            {{$getOrgCurrency->currency->symbol}} <span class="expenses"></span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="transaction-item net-results">
                                    <div class="media">
                                        <div class="avatar bg-light-warning rounded">
                                            <div class="avatar-content">
                                                <i data-feather="fast-forward" class="avatar-icon font-medium-3"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="transaction-title">Net Results</h4>
                                        </div>
                                    </div>
                                    <div class="font-weight-bolder pt-1">
                                        <h4 class="text-success">
                                            {{$getOrgCurrency->currency->symbol}} <span class="net-result"></span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Stats Card -->
                </div>
                <div class="row match-height">
                    <!--Map-->
                    <div class="col-lg-6 col-12 mb-2">
                        <div class="card">
                            <div class="chart-wrapper geojson">
                                <div class="shimmer-wrapper">
                                    <div class="shimmer"></div>
                                </div>
                            </div>
                            <div class="leaflet-map" id="geojson"></div>
                        </div>
                    </div>
                    <!--/Map-->
                    <div class="col-lg-6 col-12">
                        <div class="row match-height">
                            <!--Segment Breakup-->
                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="card">
                                    <div class="chart-wrapper segment-breakup-chart">
                                        <div class="shimmer-wrapper">
                                            <div class="shimmer"></div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <h4 class="card-title mb-75">Segment Breakup - FY <span
                                                class="selected-date"></span></h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="segment-breakup-chart"></div>
                                    </div>
                                </div>
                            </div>
                            <!--/Segment Breakup-->
                            <!-- Branches Loss Ratio -->
                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="card card-transaction">
                                    <div class="chart-wrapper geojson">
                                        <div class="shimmer-wrapper">
                                            <div class="shimmer"></div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <h4 class="card-title">Branch Performance - FY <span
                                                class="selected-date"></span> <br>
                                            <span class="text-secondary pt-1">Loss ratio by branches</span>
                                        </h4>
                                    </div>
                                    <div class="card-body data">

                                    </div>
                                </div>
                            </div>
                            <!--/ Branches Loss Ratio -->
                        </div>
                    </div>
                </div>
                <!--/Map-->
                <!--Business Snapshot Chart-->
                <div class="row match-height">
                    <div class="col-12 col-xl-12">
                        <div class="card">
                            <div class="chart-wrapper business-snapshot-chart">
                                <div class="shimmer-wrapper">
                                    <div class="shimmer"></div>
                                </div>
                            </div>
                            <div class="card-header d-flex justify-content-between">
                                <div class="card-title mb-0">
                                    <h4 class="card-title">Business Snapshot - FY <span class="selected-date"></span>
                                    </h4>
                                </div>
                            </div>
                            <div class="card-body custom-chart-card">
                                <ul class="nav nav-tabs widget-nav-tabs pb-1 gap-4 mx-1 d-flex flex-nowrap"
                                    role="tablist">
                                    <li class="nav-item">
                                        <a onclick="renderBusinessSnapshot()" href="javascript:void(0);"
                                            class="nav-link btn active d-flex flex-column align-items-center justify-content-center"
                                            role="tab" data-toggle="tab" data-target="#navs-orders-id"
                                            aria-controls="navs-orders-id" aria-selected="true">
                                            <div class="badge bg-label-secondary rounded p-2">
                                                <i data-feather='bar-chart'></i>
                                            </div>
                                            <h6 class="tab-widget-title mb-0 mt-2">Premium</h6>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a onclick="renderBusinessSnapshot()" href="javascript:void(0);"
                                            class="nav-link btn d-flex flex-column align-items-center justify-content-center"
                                            role="tab" data-toggle="tab" data-target="#navs-sales-id"
                                            aria-controls="navs-sales-id" aria-selected="false">
                                            <div class="badge bg-label-secondary rounded p-2">
                                                <i data-feather='dollar-sign'></i>
                                            </div>
                                            <h6 class="tab-widget-title mb-0 mt-2">Claims</h6>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a onclick="renderBusinessSnapshot()" href="javascript:void(0);"
                                            class="nav-link btn d-flex flex-column align-items-center justify-content-center"
                                            role="tab" data-toggle="tab" data-target="#navs-profit-id"
                                            aria-controls="navs-profit-id" aria-selected="false">
                                            <div class="badge bg-label-secondary rounded p-2">
                                                <i data-feather='bar-chart-2'></i>
                                            </div>
                                            <h6 class="tab-widget-title mb-0 mt-2">Loss Ratio</h6>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content p-0 ms-0 ms-sm-2">
                                    <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
                                        <div id="businessSnapshotChartsTabsPremium"></div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-sales-id" role="tabpanel">
                                        <div id="businessSnapshotChartsTabsClaims"></div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-profit-id" role="tabpanel">
                                        <div id="businessSnapshotChartsTabsLossRatio"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/Business Snapshot Chart-->
                    <!--Run provisios button--->
                    <div class="text-right mb-5 sticky-btn-container">
                        <div class="sticky-btn">
                            <button class="btn btn-primary" type="button" data-toggle="modal"
                                data-target="#run-provision">+ New Provision</button>
                        </div>
                    </div>
                    <!--Run provisios button--->

                </div>
            </section>
            <!-- Dashboard Ecommerce ends -->
            <!-- Run Provisoin Modal -->
            <div class="modal fade text-left" id="run-provision" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel120" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel120">Provision calculation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('user.run-provision') }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="type" value="provision">
                                <p>Calculate provision as at the specified date. Last calculation was carried out on
                                    <span class="text-primary">
                                        {{ $lastProvision }}
                                    </span>
                                </p>
                                <input type="text" id="pd-disable" class="form-control valuation-date"
                                    name="valuation_date" placeholder="{{ $lastSync }}" />
                                <span class="text-danger" id="error-message"></span>
                            </div>
                            <div class="modal-footer">
                                @authorize('execute-provision', true)
                                <button type="submit" class="btn btn-primary" data-toggle="modal" id="onshownbtn"
                                    data-target="#onshown" {{ $provisionAllowed ? '' : 'disabled' }}>

                                    <i data-feather='refresh-cw'></i>
                                    &nbsp; Run

                                </button>
                                @endauthorize
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Run Provisoin  Modal -->
        </div>
    </div>
    <div id="route" data-route="{{route('user.filters')}}"></div>
    <div data-currency="{{$getOrgCurrency->currency->symbol}}" data-json="" id="jsonData"></div>

</div>
<!-- END: Content-->
@endSection

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets/css/plugins/forms/pickers/form-pickadate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/maps/map-leaflet.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/maps/leaflet.min.css') }}">
@endSection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>

<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.min.js') }}"></script>

<script src="{{ asset('app-assets/js/scripts/components/components-modals.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/charts/custom-charts.js')}}"></script>
<script src="{{ asset('app-assets/vendors/js/maps/leaflet.min.js')}}"></script>
<script src="{{ asset('assets/js/dashboard-fetch.js') }}"></script>

<!--check filteration-->
<script>
    @if ($report != '' && $report->is_updated == 1)
        filters = JSON.parse(@json($report->filters))
        results = JSON.parse(@json($report->result))
        displayFilter(filters)
        displayData(results)
    @else
        setFilter();
        fetch_data();
    @endif

    $(document).ready(function () {
        var valuationDateInput = $('.valuation-date');

        valuationDateInput.pickadate({
            selectMonths: true,
            selectYears: true,
        });

        // Manually trigger validation on form submission
        $('form').submit(function () {
            if (valuationDateInput.val() === '') {
                var message = 'Please select a date';
                $('#error-message').html(message);
                return false;
            }
        });

        // Custom multi select
        $('.custom-multiple').on('select2:close', function () {
            let select = $(this);
            // Get the count of selected options
            let selectedValues = select.select2('data').map(function (option) {
                return option.text;
            });

            // Call the function to display the selected options message
            displaySelectedOptionsMessage(select, selectedValues);
        });


    });
    $(window).on('load', function () {
        compactMenu = true;
        $.app.menu.init(compactMenu);
    });
</script>
@endSection