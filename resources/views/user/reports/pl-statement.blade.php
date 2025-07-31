@extends('user.layouts.app')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">Reports
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <div class="alert alert-danger error-wrapper" style="display: none" role="alert">
                <div class="alert-body error">

                </div>
            </div>
            <!-- Basic tabs start -->
            <section id="basic-tabs-components">
                <div class="row match-height">
                    <!-- Basic Tabs starts -->
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-pane active" id="Dashboard" aria-labelledby="insurance-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4 class="card-title text-center mb-0" id="statement-heading">
                                                <span id="period-text">{{request()->slug}} Statement for the Period </span>
                                                <span class="dropdown-indicator" onclick="toggleDropdown()">&#9660;</span>
                                            </h4>
                                            <div class="row justify-content-center">
                                                <div class="col-md-5 offset-md-3 col-sm-5 offset-3 col-lg-6 offset-lg-5">
                                                    <div id="report-type-dropdown" class="dropdown-content">
                                                        <ul>
                                                            @if(request()->slug != 'BS')
                                                            <li><a href="{{ route('report.index',['slug' => 'BS']) }}">Financial Position</a></li>
                                                            @endif
                                                            @if(request()->slug != 'PNL')
                                                            <li><a href="{{ route('report.index',['slug' => 'PNL']) }}">Profit & Loss</a></li>
                                                            @endif
                                                            @if(request()->slug != 'SOP')
                                                            <li><a href="{{ route('report.index',['slug' => 'SOP']) }}">Statement of Premiums</a></li>
                                                            @endif
                                                            @if(request()->slug != 'SOC')
                                                            <li><a href="{{ route('report.index',['slug' => 'SOC']) }}">Statement of Claims</a></li>
                                                            @endif
                                                            @if(request()->slug != 'SOE')
                                                            <li><a href="{{ route('report.index',['slug' => 'SOE']) }}">Statement of Expenses</a></li>
                                                            @endif
                                                            @if(request()->slug != 'LRC')
                                                            <li><a href="{{ route('report.index',['slug' => 'LRC']) }}">Liability for Remaining Coverage</a></li>
                                                            @endif
                                                            @if(request()->slug != 'LIC')
                                                            <li><a href="{{ route('report.index',['slug' => 'LIC']) }}">Liability for Incurred Claims</a></li>
                                                            @endif
                                                            @if(request()->slug != 'BREAKUP')
                                                            <li><a href="{{ route('report.index',['slug' => 'BREAKUP']) }}">Breakup</a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                    <form id='report' action="{{route('report.download')}}">
                                        @csrf
                                        <div class="row pb-2">
                                            <input type="hidden" name="type" value="{{request()->slug}}">
                                            <div class="col-12 col-md-4">
                                                <label class="form-label" for="period">Period &nbsp;</label>
                                                <select class="select2 form-control" id="period"  name="accounting_year">
                                                    @foreach ($accountingYears as $accountingYear)
                                                    <option value="{{ $accountingYear->id }}">
                                                        {{ $accountingYear->year }}
                                                    </option>
                                                    @endforeach

                                                </select>
                                                <div class="text-danger" id="accounting-year-error"></div>
                                            </div>
                                            @if ((request()->slug == 'BS') || (request()->slug == 'BREAKUP'))
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group position-relative">
                                                        <label for="val-date">As at</label>
                                                        <input type="text" id="date_range" name="date_range" class="form-control accounting_year flatpickr-basic" placeholder="YYYY-MM-DD" />
                                                        <div class="text-danger" id="date-range-error"></div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label" for="portfolio">Business type &nbsp;</label>
                                                    <select class="select2 form-control" name="business_type" id="business-type">
                                                        <option value='C' selected>Conventional</option>
                                                    </select>
                                                    <div class="text-danger" id="business-type-error"></div>
                                                </div>
                                            @else
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="fp-range">Date range</label>
                                                        <input type="text" id="date_range" name="date_range" class="form-control accounting_year" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                                                        <div class="text-danger" id="date-range-error"></div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label" for="portfolio">Portfolio&nbsp;</label>
                                                    <select class="select2 form-control" id="portfolio" name="portfolio">
                                                        <option value="ALL">All lines</option>
                                                        @foreach ($portfolios as $porfolio)
                                                        <option value="{{ $porfolio->id }}">
                                                            {{ $porfolio->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="text-danger" id="portfolio-error"></div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="row pb-2">
                                            <div class="col-12 col-md-6">

                                                <button type="button" class="btn btn-primary form-control" id="filter" {{$reportNotAllowed ? 'disabled' : ''}}>Update</button>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <button type="button" class="btn btn-outline-primary waves-effect waves-light form-control" id="export-csv">
                                                    <span class="spinner-grow spinner-grow-sm me-1" id="spinner" role="status" style="display: none" aria-hidden="true"></span>
                                                    <i data-feather='download' id="download-feather"></i>
                                                    <span id="btn-text">Export CSV</span>
                                                  </button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- Striped rows start -->
                                    <div class="table-responsive">
                                        <table class="table table-hover table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th id="current-year"></th>
                                                    <th id="compare-year"></th>

                                                </tr>
                                            </thead>
                                            <tbody id="report-body">

                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Striped rows end -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Basic Tabs ends -->
                </div>
            </section>
        </div>
    </div>
</div>
<!-- Modal -->
<x-report-loader-modal route="{{route('report.filter')}}" />
<div id="start" data-start="{{$accountingYears->first()?->start_date}}"></div>
<div id="end" data-end="{{$accountingYears->first()?->end_date}}"></div>
<!-- Modal -->
<!-- END: Content-->
@endSection

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css') }}">
@endSection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('assets/js/report-fetch.js') }}"></script>
<script>
    $(document).ready(function() {
        end_date = '{{$accountingYears->first()?->end_date}}',
        start_date = '{{$accountingYears->first()?->start_date}}'
    })
</script>


<script>
    $(document).ready(function() {
        $(document).on('click', '#export-csv', function(){
            if (!validateFilters()) {
                return;
            }
            preparing_state('on', this)
            form = $('#report').serialize()
            console.log(form)
            $.ajax({
                'url' : "{{route('report.download')}}",
                'type' : 'get',
                'data' : form,
                'success' : function(res) {
                    $('.error-wrapper').hide();
                    // Create a temporary anchor element
                    var link = document.createElement('a');
                    link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(res.content);
                    link.download = res.filename; // Set your filename here

                    // Trigger the download
                    document.body.appendChild(link);
                    link.click();

                    // Clean up
                    document.body.removeChild(link);
                    preparing_state('off', this)
                },
                error: function(err) {
                    $('.error-wrapper').show();
                    $('.error').html(err.responseJSON.message);
                    preparing_state('off', this)
                }
            })
        });
        function preparing_state(prepare, button){
            spinner = $("#spinner")
            download_icon = $("#download-feather")
            button_text = $("#btn-text")

            if(prepare == 'on'){
                spinner.show();
                download_icon.hide()
                $("#export-csv").prop('disabled', true);
                button_text.html('Preparing CSV')
            }else
            {
                spinner.hide();
                download_icon.show()
                $("#export-csv").prop('disabled', false);
                button_text.html('Export CSV')
            }
        }
        $(document).on('change', '#period', function() {
            fetch_year($(this).val())
        })
        var type = "{{request()->slug}}"
        var filters = ""
        if (type == 'BS' || type == 'BREAKUP'  ) {
            var flatpickrInstance = flatpickr(".accounting_year", {
                maxDate: '{{$accountingYears->first()?->end_date}}',
                minDate: '{{$accountingYears->first()?->start_date}}'
            });

        } else {
            var flatpickrInstance = flatpickr(".accounting_year", {
                mode: "range",
                maxDate: '{{$accountingYears->first()?->end_date}}',
                minDate: '{{$accountingYears->first()?->start_date}}'
            });
        }

        @if ($report != '' && $report->is_updated == 1)
            displayData(JSON.parse(@json($report->result)))
            filters = JSON.parse(@json($report->filters))
            displayFilter(filters)
        @endif




        function displayFilter(filters)
        {
            $('#period').val(filters['period']).change()
            $('#portfolio').val(filters['portfolio']).change()
        }
        function fetch_year(year) {
            url = '{{ route("year.fetch", ["accounting_year" => ":year"])}}'.replace(':year', year)

            $.ajax({
                'url' : url,
                'type' : 'get',
                'success' : function(res) {
                    flatpickrInstance.clear()
                    flatpickrInstance.set('maxDate',res.end_date)
                    flatpickrInstance.set('minDate',res.start_date)
                    if(filters){
                        flatpickrInstance.setDate(filters['date_range']);
                        $('#date-time-range').html(filters['date_range']);
                        filters = null
                    }
                },
                'error' : function(err) {
                    console.log(err)
                }

            })
        }

    })

    // Dropdown toggle
    function toggleDropdown() {
        var dropdown = document.getElementById("report-type-dropdown");
        var indicator = document.querySelector(".dropdown-indicator");

        if (dropdown.style.display === "block") {
            dropdown.style.display = "none";
            indicator.innerHTML = "&#9660;";
        } else {
            dropdown.style.display = "block";
            indicator.innerHTML = "&#9650;";
        }
    }

</script>
@endSection
