@extends('user.layouts.app')

@section('content')
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
                <form id="dashboard" data-aos="fade-up">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 col-xl-2 col-lg-2 col-sm-12">
                            <div class="form-group ">
                                <label for="accounting-year">Accounting year</label>
                                <select class="selectpicker form-control" data-live-search="true" data-size="5"
                                    id="accounting-year" name='accounting_year_id' data-none-selected-text="Select">
                                    @foreach ($accountingYears as $accountingYear)
                                        <option value="{{CustomHelper::encode($accountingYear->id) }}">
                                            {{ $accountingYear->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-2 col-lg-2 col-sm-12">
                            <div class="form-group ">
                                <label for="portfolio">Portfolios</label>
                                <select class="selectpicker form-control" id="portfolio" multiple="multiple"
                                    name='portfolio_id[]' data-selected-text-format="count > 1"
                                    data-count-selected-text="+{0} more" data-live-search="true" data-size="5"
                                    data-none-selected-text="Select">
                                    <option value="All" selected>All</option>
                                    @foreach ($portfolios as $portfolio)
                                        <option value="{{CustomHelper::encode($portfolio->id) }}">
                                            {{ $portfolio->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="version" value="v2">

                        <div class="col-md-4 col-xl-2 col-lg-2 col-sm-12">
                            <div class="form-group ">
                                <label for="branch">Branch</label>
                                <select class="selectpicker form-control" data-live-search="true" data-size="5"
                                    id="branch" name='branch_id' data-none-selected-text="Select">
                                    <option value="All">All</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{CustomHelper::encode($branch->id) }}">
                                            {{ $branch->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-2 col-lg-2 col-sm-12">
                            <div class="form-group ">
                                <label for="business-type">Business type</label>
                                <select class="selectpicker form-control" data-live-search="true" data-size="5"
                                    id="business-type" name='business_type_id' data-none-selected-text="Select">
                                    @foreach ($businessTypes as $businessType)
                                        <option value="{{CustomHelper::encode($businessType->id) }}">
                                            {{ $businessType->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-2 col-lg-2 col-sm-12">
                            <div class="form-group ">
                                <label for="output-type">Output type</label>
                                <select class="selectpicker form-control" data-live-search="true" data-size="5"
                                    id="output-type" name='output_type_id' data-none-selected-text="Select">
                                    <option value="gross">Gross </option>
                                    <option value="net">Net </option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-2 col-lg-2 col-sm-12">
                            <div class="row filters-btn">
                                <div class="col-md-6 col-xl-6 col-lg-6 col-sm-6 p-0">
                                    <button class="btn add-new btn-primary mr-1 w-100 d-md-block" type="button"
                                        id="filters">
                                        <span class="d-md-none">Filter</span>
                                        <i data-feather="filter" class="d-none d-md-inline"></i>
                                    </button>
                                </div>
                                <div class="col-md-6 col-xl-6 col-lg-6 col-sm-6 p-0">
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
                <!-- Stat Cards Start -->
                <div class="row match-height" id="stat-cards">
                    <div class="col-sm-6 col-lg-3 " data-aos="fade-up" id="card-1">
                        <div class="card card-border-shadow-primary">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-1 pb-1">
                                    <div class="avatar me-2">
                                        <span class="avatar-content rounded bg-label-primary">
                                        <i data-feather="trending-up" class="avatar-icon font-medium-3">
                                        </i>                                     
                                        </span>
                                    </div>
                                    <h4 class="ms-1 mb-0"></h4>
                                </div>
                                <h5>Net Revenue</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 " data-aos="fade-up" data-aos-delay="100" id="card-2">
                        <div class="card card-border-shadow-warning">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-1 pb-1">
                                    <div class="avatar me-2">
                                        <span class="avatar-content rounded bg-label-primary">
                                        <i data-feather="trending-down" class="avatar-icon font-medium-3"></i>
                                        </span>
                                    </div>
                                    <h4 class="ms-1 mb-0"></h4>
                                </div>
                                <h5>Net Claims</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 " data-aos="fade-up" data-aos-delay="200" id="card-3">
                        <div class="card card-border-shadow-danger">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-1 pb-1">
                                    <div class="avatar me-2">
                                        <span class="avatar-content rounded bg-label-primary">
                                        <i data-feather="credit-card" class="avatar-icon font-medium-3"></i>

                                        </span>
                                    </div>
                                    <h4 class="ms-1 mb-0"></h4>
                                </div>
                                <h5>Net Expenses</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 " data-aos="fade-up" data-aos-delay="300" id="card-4">
                        <div class="card card-border-shadow-info">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-1 pb-1">
                                    <div class="avatar me-2">
                                        <span class="avatar-content rounded bg-label-primary">
                                        <i data-feather="bar-chart-2" class="avatar-icon font-medium-3"></i>
                                        </span>
                                    </div>
                                    <h4 class="ms-1 mb-0"></h4>
                                </div>
                                <h5>Net Results</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stat Cards End -->
                <div class="row match-height">
                    <!-- Revenue Report -->
                    <div class="col-xl-9 col-lg-12 col-md-12 " data-aos="fade-up" data-aos-delay="500">
                        <div class="card" style="min-height:200px">
                            <div class="card-body p-0">
                                <div class="row row-bordered g-0" style="width:100%; height:100%">
                                    <div class="col-12 position-relative p-5">
                                        <div class="card-header d-inline-block p-0 text-wrap position-absolute">
                                            <h5 class="m-0 card-title">Revenue Report</h5>
                                        </div>
                                        <div id="revenue-loading">
                                            <div class="lottie-container" id="revenue-lottie">
                                                <lottie-player src="{{ asset('app-assets/chart-loading-3.json') }}"
                                                    background="transparent" speed="1" loop autoplay></lottie-player>
                                            </div>
                                        </div>
                                        <div id="totalRevenueChart" class="mt-n1 "></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Revenue Report -->
                    <!-- Net Ratios -->
                    <div class="col-xl-3 col-lg-12 col-md-12" data-aos="fade-up" data-aos-delay="600">
                        <div class="row match-height">
                            <div class="col-xl-12 col-md-6 col-sm-6 ">
                                <div class="card " style="min-height:200px">

                                    <div class="card-body" id="expenses-chart-0">
                                        <div id="expense-chart-1-loading">
                                            <div class="lottie-container" id="expense-chart-1-lottie">
                                                <lottie-player src="{{ asset('app-assets/chart-loading-3.json') }}"
                                                    background="transparent" speed="1" loop autoplay></lottie-player>
                                            </div>
                                        </div>
                                        <div id="expensesChart0"></div>
                                        <div class="mt-0 text-center">
                                            <small class="text-muted mt-3 chartlabel"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-6 col-sm-6">
                                <div class="card " style="min-height:200px">

                                    <div class="card-body" id="expenses-chart-1">
                                        <div id="expense-chart-2-loading">
                                            <div class="lottie-container" id="expense-chart-2-lottie">
                                                <lottie-player src="{{ asset('app-assets/chart-loading-3.json') }}"
                                                    background="transparent" speed="1" loop autoplay></lottie-player>
                                            </div>
                                        </div>
                                        <div id="expensesChart1"></div>
                                        <div class="mt-0 text-center">
                                            <small class="text-muted mt-3 chartlabel"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- UW Profitability Start -->
                <div class="row match-height">

                    <!-- Profitability -->
                    <div class="col-xl-7 col-lg-12 col-md-12 " data-aos="fade-up" data-aos-delay="500">
                        <div class="card" style="min-height:400px">
                            <div class="card-body p-0">
                                <div class="row row-bordered g-0" style="width:100%; height:100%">
                                    <div class="col-12 position-relative p-3">
                                        <div class="card-header d-inline-block p-0 text-wrap position-absolute">
                                            <h5 class="m-0 card-title">UW Profitibility</h5>
                                        </div>
                                        <div id="profitibility-loading">
                                            <div class="lottie-container" id="profitibility-lottie">
                                                <lottie-player src="{{ asset('app-assets/chart-loading-3.json') }}"
                                                    background="transparent" speed="1" loop autoplay></lottie-player>

                                            </div>
                                        </div>
                                        <div id="uwProfitibility" class="mt-n1 "></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Profitability -->
                    <!-- Written Premium -->
                    <div class="col-xl-5 col-lg-12 col-md-12" data-aos="fade-up" data-aos-delay="600">
                        <div class="card" style="min-height:400px">
                            <div class="card-body pt-3 pb-3 pl-2 pr-2`">
                                <div class="card-header d-inline-block p-0 text-wrap position-absolute">
                                    <h5 id="outputTitle" class="m-0 card-title">Written Premium</h5>

                                </div>
                                <div id="writtenPremium-loading" style="justify-content: center;">
                                    <div class="lottie-container" id="writtenPremium-lottie">
                                        <!-- <lottie-player src="{{ asset('app-assets/chart-loading-3.json') }}"
                                            background="transparent" speed="1" loop autoplay></lottie-player> -->
                                    </div>
                                </div>
                                <div id="writtenPremiumList">

                                </div>
                                <!-- <div class="row row-bordered g-0" style="width:100%; height:100%">
                                    <div class="col-12 position-relative p-3">

                                        <div id="writtenPremiumChart"></div>
                                    </div>

                                </div> -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- UW Profitability End -->

            </section>
        </div>
        <!-- Dashboard Ecommerce ends -->
        <div class="fab-container">
            <div class="fab-options">
                <x-provision-button :isLocked="$isLocked" />
            </div>
            <button class="fab-btn">
                <i data-feather="plus"></i>
            </button>
        </div>
        <!-- <div class="text-right mb-5 sticky-btn-container">
            <div class="sticky-btn">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#run-provision">+ New
                    Provision</button>
            </div>
        </div> -->
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
                    <form action="{{ route('user.run-provision') }}"  id="provision-form"  method="post">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="type" value="provision">
                            <p>Calculate provision as at the specified date. Last calculation was carried out on
                                <span class="text-primary">
                                    {{ $lastProvision }}
                                </span>
                            </p>
                            <input type="text" id="pd-disable" class="form-control valuation-date" name="valuation_date"
                                placeholder="{{ $lastSync }}" />
                            <span class="text-danger" id="error-message"></span>
                        </div>
                        <div class="modal-footer">
                            @authorize('execute-provision', true)
                            <button type="submit" class="btn btn-primary provisionButton" data-toggle="modal" id="onshownbtn"
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

         <!-- Run Opening Modal -->
         <div class="modal fade text-left" id="run-opening" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel120" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel120"> Run Year End Closing </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('user.opening') }}"  id="opening-form"  method="post">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="type" value="opening">
                            <p>
                                Click to run Year End Closing
                            </p>
                            <span class="text-danger" id="error-message"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather='refresh-cw'></i>&nbsp; Run
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/ Run Opening  Modal -->
    </div>
</div>
<div id="route" data-route="{{route('user.filters')}}"></div>
<div id="jsonData"></div>
@endSection

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets/css/plugins/forms/pickers/form-pickadate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/maps/map-leaflet.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/maps/leaflet.min.css') }}">

<!-- Animate on Scroll CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/aos/aos.css') }}">

<!-- Bootstrap Select CSS -->
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets/vendors/css/bootstrap-select/bootstrap-select.min.css') }}">

<!-- Custom Dashboard CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('/css/dashboard.css') }}">
@endSection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/components/components-modals.js') }}"></script>

<!-- Animate on Scroll JS -->
<script src="{{ asset('app-assets/vendors/js/aos/aos.js')}}"></script>

<!-- Lottie Animation JS -->
<script src="{{ asset('app-assets/vendors/js/lottie/lottie.min.js')}}"></script>
<script src="{{ asset('app-assets/vendors/js/lottie/lottie-player.js')}}"></script>

<!-- Bootstrap Select CSS -->
<script src="{{ asset('app-assets/vendors/js/bootstrap-select/bootstrap-select.min.js')}}"></script>

<!-- Custom JS -->
<script>
    var parsedJsonDataSample;
    @if($report)
        parsedJsonDataSample = JSON.parse(@json($report->result));
        $('#jsonData').attr({
            'data-json': @json($report->result),
            'data-currency-symbol': @json($currency->symbol)
        });
    @endif
</script>

<script src="{{ asset('assets/js/dashboard-fetch-v2.js') }}"></script>

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

        var provisionDate = "{{ $lastProvision ?? null }}";
        var transitionDate = "{{ $transition_date  }}";
        
        var minDate = null;

        if (provisionDate) {
            let provDate = new Date(provisionDate);
            provDate.setDate(provDate.getDate() + 1); 
            minDate = provDate;
        } else if (transitionDate) {
            minDate = new Date(transitionDate);
        }

        var selectedDate = minDate ? new Date(minDate) : new Date();

        valuationDateInput?.pickadate({
            selectMonths: true,
            selectYears: true,
            min: minDate, 
        }).pickadate('picker').set('select', selectedDate);

        // Manually trigger validation on form submission
        $('#provision-form').submit(function () {
            if (valuationDateInput.val() === '') {
                var message = 'Please select a date';
                $('#error-message').html(message);
                return false;
            }
        });
    });
    // Fix User dropdown z-index fix onscroll
    $(window).on('scroll', function (e) {
        var height = $(window).scrollTop();
        if (height < 50) {
            $(".header-navbar").css("z-index", 15);
        } else {
            $(".header-navbar").css("z-index", 10);

        }
    });
    // Move FAB on scroll(Fix : Overlapping Scroll to Top)
    $(window).on('scroll', function (e) {
        var height = $(window).scrollTop();
        if (height < 50) {
            $(".fab-container").css("bottom", 30);
            $(".fab-container").css("transistion", "all 0.2s ease-in-out");
        } else {
            $(".fab-container").css("bottom", 90);
            $(".fab-container").css("transistion", "all 0.2s ease-in-out");

        }
    });
    $(document).ready(function () {
        $('.fab-btn').on('click', function (event) {
            event.stopPropagation(); // Prevents the click event from bubbling up to the document
            $('.fab-options').toggleClass('show');

            if ($('.fab-options').hasClass("show")) {
                $(".fab-btn").css("transform", "rotate(45deg) scale(0.9)")
                $(".fab-btn").css("background", "#4d80c1")
            } else {
                $(".fab-btn").css("transform", "rotate(0) scale(1)")
                $(".fab-btn").css("background", "#003399")

            }
        });

        $(document).on('click', function (event) {
            if ($('.fab-options').hasClass('show')) {

                // Check if the clicked element is outside the fab-container
                if (!$(event.target).closest('.fab-container').length) {

                    $('.fab-options').removeClass('show');
                    $(".fab-btn").css("transform", "rotate(0)")
                }
            }
        });

        $("#newProvisionBtn").on("click", function (event) {
            $("#run-provision").modal("show")
            $('.fab-options').removeClass('show');
            $(".fab-btn").css("transform", "rotate(0)")
        });

        $("#yearEndClosingBtn").on('click',function(){
            $("#run-opening").modal("show")
            $('.fab-options').removeClass('show');
            $(".fab-btn").css("transform", "rotate(0)")
        });
    });

    // Call the function immediately to check the scroll position on page load
    $(window).trigger('scroll');

    // Initialize Animate on Scroll
    AOS.init({ once: true });
</script>
@endSection
