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
                        <h2 class="content-header-title float-left mb-0">Trial balance</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">Accounting</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <section>
                <form action="{{ route('trial.download') }}" method='get' id="trial" style="width:100%">
                    @csrf
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="accounting-year">Accounting year</label>
                                            <select class=" form-control" id="accounting-year"
                                                name="accounting_year_id">
                                                <option disabled selected>Select Accounting Year</option>
                                                @foreach ($accountingYears as $accountingYear)
                                                    <option value="{{ $accountingYear->id }}">
                                                        {{ $accountingYear->year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" id="accounting-year-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="portfolio">Portfolio</label>
                                            <select class=" form-control" id="portfolio" name="portfolio_id">
                                                <option value="All">All</option>
                                                @foreach ($porfolios as $porfolio)
                                                    <option value="{{ $porfolio->id }}">
                                                        {{ $porfolio->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" id="portfolio-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="branch">Branch</label>
                                            <select class=" form-control" id="branch" name="branch_id">
                                                <option value="All">All</option>
                                                @foreach ($branches as $branch)
                                                    <option value='{{ $branch->id }}'>
                                                        {{ $branch->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" id="branch-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="business-type">Business type</label>
                                            <select class=" form-control" name="business_type_id" id="business-type">
                                                <option disabled selected>Select Business Type</option>
                                                @foreach ($businessTypes as $businessType)
                                                    <option value="{{ $businessType->id }}">
                                                        {{ $businessType->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" id="business-type-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="fp-range">Date range</label>
                                            <input type="text" id="date-range" name="date_range"
                                                class="form-control flatpickr-range accounting_year"
                                                placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                                            <div class="text-danger" id="date-range-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="updatefilter">Filter</label>
                                            <button class="btn btn-icon btn-primary form-control" type="button"
                                                id="filter">Update filter </button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="export">Export</label>
                                            <button type="button"
                                                class="btn btn-outline-primary waves-effect waves-light form-control"
                                                id="export-csv">
                                                <span class="spinner-grow spinner-grow-sm me-1" id="spinner"
                                                    role="status" style="display: none" aria-hidden="true"></span>
                                                <i data-feather='download' id="download-feather"></i>
                                                <span id="btn-text">Export CSV</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="content-header row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive px-1 ">
                                    <table class="table table-hover data_table" id="trial-table">
                                        <thead>
                                            <tr>
                                                <th>GL Code</th>
                                                <th>Description</th>
                                                <th>Opening</th>
                                                <th>Credit</th>
                                                <th>Debit</th>
                                                <th>Closing</th>
                                            </tr>
                                        </thead>
                                        <tbody id="trial-body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice repeater -->
                    </div>
                </div>

            </section>
        </div>
    </div>
</div>
<!-- Modal -->
<x-report-loader-modal route="{{ route('trial.filter') }}" />
<div id="start" data-start="{{ $accountingYears->first()?->start_date }}"></div>
<div id="end" data-end="{{ $accountingYears->first()?->end_date }}"></div>
<div id="download" data-route="{{ route('trial.download') }}"></div>
<!-- Modal -->
<!-- END: Content-->
@endSection

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css') }}">
@endSection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/trial-fetch.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

<script>
    $(document).ready(function () {
        end_date   = '{{ $accountingYears->first()?->end_date }}',
        start_date = '{{ $accountingYears->first()?->start_date }}'
    })

    $(document).ready(function () {
        $(document).on('click', '#export-csv', function () {
            if (!validateFilters()) {
                return;
            }
            preparing_state('on', this)
            form = $('#trial').serialize()
            $.ajax({
                'url': "{{ route('trial.download') }}",
                'type': 'get',
                'data': form,
                'success': function (res) {
                    var link  = document.createElement('a');
                    link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(res.content);
                    link.download = res.filename; // Set your filename here

                    // Trigger the download
                    document.body.appendChild(link);
                    link.click();

                    // Clean up
                    document.body.removeChild(link);
                    preparing_state('off', this)
                },
                'error': function (err) {
                    console.log(err)
                }
            })
        });
        var flatpickrInstance = flatpickr(".accounting_year", {
            mode: "range",
            maxDate: '{{ $accountingYears->first()?->end_date }}',
            minDate: '{{ $accountingYears->first()?->start_date }}'
        });

        function preparing_state(prepare, button) {
            spinner       = $("#spinner")
            download_icon = $("#download-feather")
            button_text   = $("#btn-text")

            if (prepare == 'on') {
                spinner.show();
                download_icon.hide()
                $("#export-csv").prop('disabled', true);
                button_text.html('Preparing CSV')
            } else {
                spinner.hide();
                download_icon.show()
                $("#export-csv").prop('disabled', false);
                button_text.html('Export CSV')
            }
        }
        $(document).on('change', '#accounting-year', function () {
            fetch_year($(this).val())
        })
        var flatpickrInstance = flatpickr(".accounting_year", {
            mode: "range",
            maxDate: '{{ $accountingYears->first()?->end_date }}',
            minDate: '{{ $accountingYears->first()?->start_date }}'
        });

        @if ($report != '' && $report->is_updated == 1)
            displayData(JSON.parse(@json($report->result)))
            filters = JSON.parse(@json($report->filters))
            displayFilter(filters)
        @endif


        function displayFilter(filters) {
            $('#accounting-year').val(filters['period']).change()
            $('#portfolio').val(filters['portfolio']).change()
            $('#business-type').val(filters['business']).change()
            $('#branch').val(filters['branch']).change()
            $('#date-range').val(filters['date_range']).change()
        }

        function fetch_year(year) {
            url = '{{ route('year.fetch', ['accounting_year' => ':year']) }}'.replace(':year', year)

            $.ajax({
                'url': url,
                'type': 'get',
                'success': function (res) {
                    flatpickrInstance.clear()
                    flatpickrInstance.set('maxDate', res.end_date)
                    flatpickrInstance.set('minDate', res.start_date)
                    if (filters) {
                        flatpickrInstance.setDate(filters['date_range']);
                        filters = null
                    }
                },
                'error': function (err) {
                    console.log(err)
                }

            })
        }

    })
</script>
@endSection
