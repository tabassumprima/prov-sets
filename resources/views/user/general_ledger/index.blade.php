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
                            <h2 class="content-header-title float-left mb-0">General Ledger</h2>
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
                <div class="alert alert-danger error-wrapper" style="display: none" role="alert">
                    <div class="alert-body error">

                    </div>
                </div>
                <section class="form-control-repeater">

                    <div class="row">
                        <!-- Invoice repeater -->
                        <div class="col-12">
                            <form method='GET' id="ledger-form">
                                @csrf
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-12 col-md-4">
                                                        <div class="form-group">
                                                            <label for="accounting-year">Accounting year</label>
                                                            <select class="select2 form-control" id="accounting-year"
                                                                name='accounting_year_id'>
                                                                @foreach ($accountingYears as $accountingYear)
                                                                    <option value="{{ $accountingYear->id }}">
                                                                        {{ $accountingYear->year }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="text-danger" id="accounting-year-error"></div>
                                                        </div>

                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <div class="form-group">
                                                            <label for="portfolio">Portfolios</label>
                                                            <select class="select2 form-control" id="portfolio"
                                                                name='portfolio_id'>
                                                                <option value="All">All</option>
                                                                @foreach ($portfolios as $portfolio)
                                                                    <option value="{{ $portfolio->id }}">
                                                                        {{ $portfolio->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="text-danger" id="portfolio-error"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-4">
                                                        <div class="form-group">
                                                            <label for="branch">Branch</label>
                                                            <select class="select2 form-control" id="branch"
                                                                name='branch_id'>
                                                                <option value="All">All</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->description }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="text-danger" id="branch-error"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-4">
                                                        <div class="form-group">
                                                            <label for="business-type">Business type</label>
                                                            <select class="select2 form-control" id="business-type"
                                                                name='business_type_id'>
                                                                @foreach ($businessTypes as $businessType)
                                                                    <option value="{{ $businessType->id }}">
                                                                        {{ $businessType->description }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="text-danger" id="business-type-error"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="fp-range">Date range</label>
                                                            <input type="text" id="date-range" name="date_range"
                                                                class="form-control flatpickr-range accounting_year"
                                                                placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                                                            <div class="text-danger" id="date-range-error"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-4">
                                                        <div class="form-group">
                                                            <label for="glcode">Account</label>
                                                            <select class="select2 form-control" id="glcode" name="gl_code_id">
                                                                <option disabled selected>Select GL</option>
                                                                @foreach ($glCodes as $glCode)
                                                                    <option value="{{ $glCode->id }}">{{ $glCode->code }}
                                                                        - {{ $glCode->description }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="text-danger" id="glcode-error"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 float-right">
                                                <div class="row">
                                                    <div class="col-12 col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="updatefilter">Filter</label>
                                                            <button class="btn btn-icon btn-primary form-control"
                                                                type="button" id="updateFilter">Update filter </button>
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
                                </div>
                            </form>
                        </div>
                        <div class="col-12">
                            <div class="card">

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="ledger-fetch">
                                            <thead>
                                                <tr>
                                                    <!-- Define your table headers here -->
                                                    <th>Date</th>
                                                    <th>Voucher</th>
                                                    <th>Description</th>
                                                    <th>Portfolio</th>
                                                    <th class="pr-2">Debit</th>
                                                    <th class="pr-2">Credit</th>
                                                    <th class="pr-2">Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Table body will be populated dynamically -->
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
    <x-report-loader-modal route="{{ route('general-ledger.filter') }}" />
    <div id="start" data-start="{{ $accountingYears->first()?->start_date }}"></div>
    <div id="end" data-end="{{ $accountingYears->first()?->end_date }}"></div>
    <div id="download" data-route="{{ route('trial.download') }}"></div>
    <!-- END: Content-->
@endSection

@section('page-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css') }}">
@endSection

@section('scripts')
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>

    <script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('assets/js/ledger-fetch.js') }}"></script>
    <script>
        $(document).ready(function() {
            end_date = '{{ $accountingYears->first()?->end_date }}',
                start_date = '{{ $accountingYears->first()?->start_date }}'
        })
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '#export-csv', function() {
                if (!validateFilters()) {
                    return;
                }
                preparing_state('on', this)
                form = $('#ledger-form').serialize()
                $.ajax({
                    'url': "{{ route('general-ledger.download') }}",
                    'type': 'get',
                    'data': form,
                    'success': function(res) {
                        // Create a temporary anchor element
                        var link = document.createElement('a');
                        link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(res
                            .content);
                        link.download = res.filename; // Set your filename here

                        // Trigger the download
                        document.body.appendChild(link);
                        link.click();

                        // Clean up
                        document.body.removeChild(link);
                        preparing_state('off', this)
                    },
                    'error': function(err) {
                        console.log(err)
                    }
                })
            });

            function preparing_state(prepare, button) {
                spinner = $("#spinner")
                download_icon = $("#download-feather")
                button_text = $("#btn-text")

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
            $(document).on('change', '#accounting-year', function() {
                fetch_year($(this).val())
            })
            var flatpickrInstance = flatpickr(".accounting_year", {
                mode: "range",
                maxDate: '{{ $accountingYears->first()?->end_date }}',
                minDate: '{{ $accountingYears->first()?->start_date }}'
            });

            function fetch_year(year) {
                url = '{{ route('year.fetch', ['accounting_year' => ':year']) }}'.replace(':year', year)

                $.ajax({
                    'url': url,
                    'type': 'get',
                    'success': function(res) {
                        flatpickrInstance.clear()
                        flatpickrInstance.set('maxDate', res.end_date)
                        flatpickrInstance.set('minDate', res.start_date)
                    },
                    'error': function(err) {
                        console.log(err)
                    }

                })
            }

            // Function to validate filters
            function validateFilters() {
                var fields = [
                    { field: $('#accounting-year'), name: 'Accounting Year' },
                    { field: $('#portfolio'), name: 'Portfolio' },
                    { field: $('#branch'), name: 'Branch' },
                    { field: $('#business-type'), name: 'Business Type' },
                    { field: $('#date-range'), name: 'Date Range' },
                    { field: $('#glcode'), name: 'GL Code' }
                ];

                var valid = true;

                for (var i = 0; i < fields.length; i++) {
                    var field = fields[i].field;
                    var fieldName = fields[i].name;

                    if (!verifyRequiredField(field, fieldName)) {
                        valid = false;
                        break; // Break the loop if any field is invalid
                    }
                }

                return valid;
            }

            route = $('#route').data('route');
            $.fn.dataTable.ext.errMode = 'throw';
            $('#updateFilter').click(function() {
                if (!validateFilters()) {
                    return;
                }
                // Show the table before making the Ajax call
                $('#ledger-fetch').show();
                if ( $.fn.dataTable.isDataTable( '#ledger-fetch' ) ) {
                    table = $('#ledger-fetch').DataTable();
                    table.destroy();
                }
                var dataTable = $('#ledger-fetch').dataTable({
                    "serverSide": true,
                    "processing": true,
                    "lengthMenu": [10, 200, 500], // Set the available page lengths
                    "pageLength": 200,
                    "ajax": {
                        "url": route,
                        "type": 'get',
                        "data": function (d) {
                            d.page = d.start / d.length + 1;
                            // Add additional parameters to the Ajax request
                            d.formData = $('#ledger-form').serialize();
                        },
                        "error": function(xhr, status, error) {
                        var tableBody = $('#ledger-fetch tbody');
                        tableBody.empty();
                        $('#ledger-fetch_processing').css('display', 'none');
                        let message = 'No data available';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            message = xhr.responseJSON.error;
                        }
                        tableBody.append('<tr><td colspan="7" class="text-center">' + message + '</td></tr>');

                }
                    },
                    "columns": [{
                            "data": "date"
                        },
                        {
                            "data": "voucher"
                        },
                        {
                            "data": "description"
                        },
                        {
                            "data": "portfolio"
                        },
                        {
                            "data": "debit",
                            "render": function(data, type, row) {
                                // Format debit values with commas
                                return formatNumber(data);
                            },
                            "className": "text-right"
                        },
                        {
                            "data": "credit",
                            "render": function(data, type, row) {
                                // Format debit values with commas
                                return formatNumber(data);
                            },
                            "className": "text-right"
                        },

                        {
                            "data": "balance",
                            "render": function(data, type, row) {
                                // Format debit values with commas
                                return formatNumber(data);
                            },
                            "className": "text-right"
                        },
                    ],
                    "drawCallback": function(settings) {
                        var api = this.api();
                        var data = settings.json;
                        if (data.recordsFiltered == 0) {
                        $('#ledger-fetch tbody').html('<tr><td colspan="7" class="text-center">No data available</td></tr>');
                    }
                    }

                });
            })

            function formatNumber(value) {
                var number = parseFloat(value);
                if (isNaN(number)) {
                    return value; // If not a number, return as is
                }
                var formatted = new Intl.NumberFormat().format(Math.abs(number));
                return number < 0 ? `(${formatted})` : formatted;
            }

        })
    </script>
@endSection
