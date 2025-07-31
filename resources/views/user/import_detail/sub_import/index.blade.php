@extends('user.layouts.app')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-10">
                            <div class="breadcrumb-wrapper w-100">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('data-import.index') }}">Data Import</a>
                                    </li>
                                    <li class="breadcrumb-item">{{ CustomHelper::encode($importDetail->id) }} </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />

                <!-- Basic tabs start -->
                <section id="basic-tabs-components">
                    <div class="row match-height">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row w-100">
                                        <div class="col-6 text-left">
                                            <h2>Import Files</h2>
                                        </div>
                                        <div class="col-6 text-right">
                                            @if ($summaryStatus->slug != 'approved' && $summaryStatus->slug != 'locked')
                                                <button class="btn add-new btn-primary" tabindex="0" type="button"
                                                    data-toggle="modal" data-target="#modals-slide-in">
                                                    <i data-feather='plus'></i>
                                                    <span> Upload Files </span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="main-content" class="file_manager">
                                        <div class="container">
                                            <div class="row">

                                                @if (count($subImports) > 0)
                                                    @foreach ($subImports as $subImport)
                                                        <!-- Ignore summary file -->
                                                        @if (basename($subImport) != '_summary.csv')
                                                            <div class="col-md-4 col-lg-3 col-xl-2 mt-4">
                                                                <!-- Adjusted column size -->
                                                                <div
                                                                    class="card file-card shadow-sm border-0 rounded-lg h-100">
                                                                    <!-- Auto-adjust height -->
                                                                    <div class="card-body p-2"> <!-- Reduced padding -->
                                                                        <div class="d-flex justify-content-end mb-2">
                                                                            <!-- Download Button -->
                                                                            <a href="{{ route('download-data-import', ['sub_import_id' => CustomHelper::encode($subImport->id)]) }}"
                                                                                class="mr-1 download-file" title="Download">
                                                                                <i data-feather="download"></i>
                                                                            </a>
                                                                            <!-- Delete Button -->
                                                                            @if ($summaryStatus->slug != 'approved' && $summaryStatus->slug != 'locked')
                                                                                <a data-route="{{ route('delete-data-import', ['sub_import_id' => CustomHelper::encode($subImport->id)]) }}"
                                                                                    class="delete delete-file"
                                                                                    title="Delete">
                                                                                    <i data-feather="trash-2"
                                                                                        style="color:red;"></i>
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                        <div class="icon text-center mb-2">
                                                                            <img src="{{ asset('app-assets/csv.svg') }}"
                                                                                alt="CSV File" class="img-fluid"
                                                                                style="height: 40px; opacity: 0.8;">
                                                                            <!-- Reduced icon size -->
                                                                        </div>
                                                                        <div class="file-name text-center">
                                                                            <small
                                                                                class="text-dark font-weight-bold d-inline-block text-wrap"
                                                                                style="word-break: break-word; font-size: 0.85rem; max-width: 100%; white-space: normal;">
                                                                                <!-- Ensure wrapping -->
                                                                                {{ $subImport->file_name }}

                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="col-12">
                                                        <div class="card shadow-sm">
                                                            <div class="card-body text-center">
                                                                <i class="text-muted"><strong>No Record Found</strong></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        @if($importStatus->slug != 'uploading')
        <!-- Modal to add new user starts-->
        <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
            <div class="modal-dialog">
                <!-- Loader -->
                <!-- Loader Modal -->
                <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content text-center">
                            <div class="modal-body">
                                <p>Uploading and processing file, please wait...</p>
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end Loader -->
                <form class="add-new-user modal-content pt-0" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title">Upload new File</h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <div class="form-group">
                            <label for="table_type" class="required"> Table Type </label>
                            <select class="form-control" name="table_type" id="table_type">
                                @foreach ($tables as $key => $type)
                                    <option value="{{ $type['table_mapping'] }}">
                                        {{ $type['view_mapping'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required"> Choose File </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="import_file" id="fileInput"
                                    accept=".csv, .xlsx, .xls" required>
                                <label class="custom-file-label" for="fileInput">Choose file</label>
                            </div>
                        </div>
                        <button type="button" id="submitForm" class="btn btn-primary mr-1 data-submit">Upload</button>
                        <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
        <!-- Modal to add new user Ends-->
        </section>
        <!-- Modal -->
        <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel120" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel120">Delete Sub Import</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete?
                    </div>
                    <form id="delete-import-file" method="post">
                        @csrf
                        @method('delete')
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
    </div>
    </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel120" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel120">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete?
                </div>
                <form id="delete-import-file" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Modal -->
    <!-- END: Content-->

    <input type="hidden" id="import_route"
        data-value="{{ route('data-import.sub_imports.index', ['data_import' => CustomHelper::encode($importDetail->id)]) }}">
    <input type="hidden" id="importStatus" value="{{ $importStatus->slug }}">
    <input type="hidden" id="store_route"
        data-value="{{ route('data-import.sub_imports.store', ['data_import' => CustomHelper::encode($importDetail->id)]) }}">
    <input type="hidden" id="check_status" data-value="{{ route('check-import-status', ['data_import' => CustomHelper::encode($importDetail->id)]) }}">
    <input type="hidden" id="auto_import" data-value="{{ $setting }}">
    @if($setting)
        <input type="hidden" id="redirect-route" value="{{ route('summaries.index') }}">
    @endif
@endSection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/css/plugins/forms/pickers/form-pickadate.min.css') }}">
@endSection

@section('scripts')
    <script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.min.js') }}"></script>

    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>


    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        var auto_import = false;
        $(document).ready(function() {
            auto_import = $("#auto_import").data('value');

            var import_status = $("#importStatus").val();
            if (import_status == 'uploading') {
                showSwalLoading();
                checkImportStatus();
            }

            // showSwalLoading();
            $('#submitForm').click(function() {
                console.log(fileInput.files.length)
                if (!fileInput.files.length) {
                    Swal.fire({
                        title: "Error!",
                        text: "Please select a file to upload",
                        icon: "error",
                        timer: 2000,
                        showConfirmButton: false,
                    })
                    return;
                }
                Swal.fire({
                    title: "Are you sure?",
                    text: "You are about to upload a file",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm();
                    }
                });

            })
        })

        function showSwalLoading() {
            let timerInterval;
            const swal = Swal.fire({
                title: "Uploading file",
                html: "Please wait while we upload",
                timerProgressBar: true,
                allowOutsideClick : false,
                didOpen: () => {
                    Swal.showLoading();
                },
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log("I was closed by the timer");
                }
            });
            return swal
        }

        function submitForm() {

            let formData = new FormData(document.getElementById('uploadForm'));

            // Step 1: Upload the file to S3 raw_zipped_data
            store_route = $('#store_route').data('value');
            $.ajax({
                url: store_route,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                allowOutsideClick : false,
                timeout: 300000,
                success: function(res) {
                    showSwalLoading();
                    checkImportStatus();
                },
                error: function(xhr) {
                    $('#loadingModal').modal('hide');
                    Swal.fire({
                        title: "Error!",
                        text: xhr.responseJSON?.message || 'Failed to upload file.',
                        icon: "error",
                    }).then(() => {
                        $('#loadingModal').modal('hide');
                        return;
                    });
                }
            });
        }

        function checkImportStatus() {
            var route = $("#check_status").data('value');
            console.log(auto_import);
            $.ajax({
                url: route,
                type: "GET",
                success: function(response) {
                    if (response.status.slug == 'started' || response.status.slug == 'completed' || response.status.slug == 'pending_import' || response.status.slug == 'pending' ) {
                        // SweetAlert2 for success message with auto-close
                        if (auto_import == false){
                            message = "File successfully uploaded. Redirecting to summary page...";
                            redirect_route = $("#redirect-route").val();
                        }
                        else{
                            message = "File successfully uploaded. It may take a few minutes to process the file.";
                        }
                        Swal.fire({
                            title: "Success!",
                            text: message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(() => {
                            if (auto_import == false) {
                                window.location.href  = redirect_route;
                            }
                            else {
                                window.location.reload();
                            }
                        });
                    } else if (response.status.slug == 'failed' || response.status.slug == 'error') {

                        // SweetAlert2 for error message with auto-close
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            allowOutsideClick: false,
                            icon: "error"
                        });
                    } else {

                        // Retry after a delay if not completed
                        setTimeout(function() {
                            checkImportStatus(); // Recursive call with a delay
                        }, 8000); // Delay of 8 seconds between checks
                    }

                },
                error: function(xhr, status, error) {
                    // Handle any errors and log to the console
                    console.error("Error:", error);
                    console.error("Status:", status);
                    console.error("XHR:", xhr);
                }
            });
        }

        $(".delete").on('click', function() {
            $("#danger").modal();
            const route = $(this).data('route');
            document.getElementById('delete-import-file').action = route;
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get the column value
            var folderStatus = $("#folderStatus").val();

            console.log(folderStatus);

            if (folderStatus === 'pending_import') {
                $(".add-new").hide();
                Swal.fire({
                    title: "Error!",
                    text: "Kindly approve summary",
                    icon: "error",
                });
            }
            if (folderStatus === 'uploading') {
                $(".add-new").click();
                $('#loadingModal').modal('show');
            }
        });

        // document.querySelectorAll('small').forEach((small) => {
        //     small.innerHTML = small.innerHTML.replace(/([._])/g, '$1<wbr>');
        // });
    </script>
@endSection
