@extends('user.layouts.app')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div id="toast-container" class="toast-container"></div>
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Disclosure</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">Disclosure</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section>
                <form action="{{ route('disclosure.download') }}" method='get' id="disclosure">
                    @csrf
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="accounting-year">Accounting year</label>
                                            <select class="select2 form-control" id="accounting-year" name="accounting_year_id">
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
                                            <label for="business-type">Business type</label>
                                            <select class="select2 form-control" name="business_type_id" id="business-type">
                                                <option disabled>Select Business Type</option>
                                                @foreach ($businessTypes as $businessType)
                                                    <option value="{{ $businessType->id }}" {{ $businessType->description == 'Conventional' ? 'selected' : '' }}>
                                                        {{ $businessType->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" id="business-type-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 offset-md-9 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="export">Export</label>
                                            <button type="button"
                                                class="btn btn-outline-primary waves-effect waves-light form-control"
                                                id="export-excel">
                                                <span class="spinner-grow spinner-grow-sm me-1" id="spinner"
                                                    role="status" style="display: none" aria-hidden="true"></span>
                                                <i data-feather='download' id="download-feather"></i>
                                                <span id="btn-text">Export Excel</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="start" data-start="{{ $accountingYears->first()?->start_date }}"></div>
<div id="end" data-end="{{ $accountingYears->first()?->end_date }}"></div>
<div id="download" data-route="{{ route('disclosure.download') }}"></div>
<!-- Modal -->
<!-- END: Content-->
@endSection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>

<script>
    $(document).ready(function () {
        end_date = '{{ $accountingYears->first()?->end_date }}'
        start_date = '{{ $accountingYears->first()?->start_date }}'
    })

    $(document).ready(function () {

        $(document).on('click', '#export-excel', function () {
            if (!validateFilters()) {
                toastr['error']('Please select account year' , 'Error!', {
                        positionClass: 'toast-top-right',
                        closeButton: true,
                        tapToDismiss: false,
                    });
                return;
            }
            preparing_state('on', this)
            form = $('#disclosure').serialize()
            $.ajax({
                url: "{{ route('disclosure.download') }}",
                type: 'GET',
                data: form,
                xhrFields: {
                    responseType: 'blob' // Set response type to blob to handle binary data
                },
                success: function (res, status, xhr) {
                    preparing_state('off', this);
                    // Create a URL for the blob
                    var url = window.URL.createObjectURL(res);
                    var a = document.createElement('a');
                    a.href = url;

                    // Get filename from the Content-Disposition header if available
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    var filename = 'downloaded-file.xlsx'; // Default filename

                        if (disposition && disposition.indexOf('filename=') !== -1) {
                            var matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(
                                disposition);
                            if (matches != null && matches[1]) {
                                filename = matches[1].replace(/['"]/g, ''); // Remove any quotes
                            }
                        }

                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url); // Clean up the URL object
                    },
                    error: function(err) {
                        preparing_state('off', this);
                        if (err.responseJSON && err.responseJSON.error) {
                            toastr['error'](err.responseJSON.error , 'Error!', {
                            positionClass: 'toast-top-right',
                            closeButton: true,
                            tapToDismiss: false,
                    });
                        } else {
                            toastr['error']('An error occurred while downloading the Excel file. Please try again.', 'Error!', {
                            positionClass: 'toast-top-right',
                            closeButton: true,
                            tapToDismiss: false,
                        });
                        }
                    }
                });
            });

            function preparing_state(prepare, button) {
                spinner = $("#spinner")
                download_icon = $("#download-feather")
                button_text = $("#btn-text")

                if (prepare == 'on') {
                    spinner.show();
                    download_icon.hide()
                    $("#export-excel").prop('disabled', true);
                    button_text.html('Preparing Excel')
                } else {
                    spinner.hide();
                    download_icon.show()
                    $("#export-excel").prop('disabled', false);
                    button_text.html('Export Excel')
                }
            }

        @if ($report != '' && $report->is_updated == 1)
            displayData(JSON.parse(@json($report->result)))
            filters = JSON.parse(@json($report->filters))
            displayFilter(filters)
        @endif

        function displayFilter(filters) {
            $('#accounting-year').val(filters['period']).change()
            $('#business-type').val(filters['business']).change()
        }

        function validateFilters() {
            var fields = [
                { field: $('#accounting-year'), name: 'Accounting Year' },
                { field: $('#business-type'), name: 'Business Type' },
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

        function verifyRequiredField(field, fieldName) {
            if (field.val() === null || field.val() === '') {
                field.next('.text-danger').text(fieldName + ' is required.');
                return false;
            } else {
                field.next('.text-danger').text('');
                return true;
            }
        }
    })
</script>
@endSection
