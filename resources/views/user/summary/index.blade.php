@extends('user.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper w-100">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">System Provision</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />
                <!-- users list start -->
                <section class="app-user-list">
                    <!-- list section start -->
                    <div class="card">
                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 mb-75">
                            <div class="col-lg-12 col-xl-6 pt-1">
                                <h2>Summaries</h2>
                            </div>
                        </div>

                        <div class="card-datatable table-responsive pt-0 pb-1 pl-1 pr-1">
                            <table class="user-list-table table data-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th>Starts At / Ends At</th>
                                        <th>Error</th>
                                        <th>Approve By</th>
                                        <th>Status</th>
                                        <th>Summary</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @foreach ($summaries as $summary)

                                    <tr>
                                        <td>{{ $summary->id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($summary->starts_at)->format('Y-m-d') }} / {{ \Carbon\Carbon::parse($summary->ends_at)->format('Y-m-d') }}</td>
                                        <td>  @if($summary->status->slug == 'error')
                                        <a href="{{ route('download-error-file', ['summary_id' => $summary->id]) }}">
                                                 Download Error File
                                            </a>
                                            @else

                                        <span> - </span>
                                         @endif</td>
                                                <td>
                                            {{ $summary->approvedBy?->name }}
                                        </td>
                                        <td><span
                                                class="badge badge-{{ $summary->status->color }}">{{ $summary->status->title }}</span>
                                        </td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-summaries="{{$summary}}" data-encoded-id="{{CustomHelper::encode($summary->id)}}" class="summary-data">
                                                View Summary
                                            </a>
                                        </td>
                                        <td>
                                            <form action="{{ route('summary.approve', $summary->id) }}" method="post">
                                                @csrf
                                                @authorize('approve-summary', true)
                                                @if($summary->status->slug == 'pending_import')
                                                    <button type="button" class="btn btn-success" onclick="verificationModal({{$summary->id}})"><i data-feather="check"
                                                    style="color:rgb(255, 255, 255);"></i></button>
                                                @endif
                                                @if ($summary->status->slug == 'pending')
                                                    <button type="button" class="btn btn-success" onclick="LockSummaryAndFolder({{$summary->id}})"><i data-feather="check"
                                                    style="color:rgb(255, 255, 255);"></i></button>
                                                @else
                                                -
                                                @endif
                                                @endauthorize
                                                @authorize('delete-summary', true)
                                                @if (!in_array($summary->status->slug, ['failed', 'started','revoked','approved', 'locked', 'error']))
                                                    <button type="button"
                                                        data-route="{{ route('summaries.destroy', [$summary->id]) }}" onclick="deleteModal({{$summary->id}})"
                                                        class="btn btn-danger delete-btn"><i
                                                        data-feather="x"
                                                        style="color:rgb(255, 255, 255);"></i></button>
                                                @endif
                                                @endauthorize
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <!-- list section end -->
                    <!-- Approved Modal -->
                    <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel33" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Approve Summary</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="verification_form" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <span>To avoid accidental approving please provide additional written consent.</span>
                                            <h5><br/>Please type <i id="vf_code" style="color:red;"></i> in the following text box to
                                                proceed </h5>
                                        </div>

                                        <div class="form-group">
                                            <input type="input" id="vf_code_input" placeholder="Verification Code"
                                                    class="form-control" oninput="verifyCode()"/>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="btn_verification" class="btn btn-primary" disabled>Approved Summary</button>
                                        <button class="btn btn-outline-primary" type="button" id="btn_loading" disabled style="display: none">
                                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            <span class="ml-25 align-middle">Preparing Signed Report...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Approved Modal -->
                    <!-- Delete Modal -->
                    <div class="modal fade text-left" id="deleteForm" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel33" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Revoke Summary</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="deletion_form" method="post">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <span>To avoid accidental revoke please provide additional written consent.</span><br>
                                            <h5><br/>Please type <i id="vf_delete_code" style="color:red;"></i> in the following text box to
                                                proceed </h5>
                                        </div>

                                        <div class="form-group">
                                            <input type="input" id="vf_delete_code_input" placeholder="Verification Code"
                                                    class="form-control" oninput="deleteCode()"/>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="btn_deletion" class="btn btn-primary" disabled>Revoke Summary</button>
                                        <button class="btn btn-outline-primary" type="button" id="btn_loading" disabled style="display: none">
                                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            <span class="ml-25 align-middle">Preparing Signed Report...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Delete Modal -->

                    <!-- Summary Modal -->
                    <div class="modal fade" id="summary-modal" tabindex="-1" role="dialog" aria-labelledby="summary-modalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h3 class="modal-title text-primary" id="summaryModalLongTitle">Summaries<br><small class="text-secondary font-weight-bold">Summary Id: <span id="import-detail-id"></span></small>&nbsp;<small class="text-secondary font-weight-bold">Start at: <span id="start-at"></span></small>&nbsp;<small class="text-secondary font-weight-bold">End at: <span id="end-at"></span></small>&nbsp;<small><span id="import-status"></span></small></h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="text-left pt-1 text-primary">Summary</h5>
                                        <div class="table-responsive">
                                            <table class="user-list-table table" id="summary-table">
                                                <thead>
                                                    <tr>
                                                        <th>Table Name</th>
                                                        <th>System Rows</th>
                                                        <th>System Sum</th>
                                                        <th>Delta Rows</th>
                                                        <th>Delta Sum</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Table rows will go here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- Summary Modal End-->

            </div>
            </section>
            <!-- users list ends -->

        </div>
    </div>
    </div>
@endSection

@section('scripts')
    <script src="{{ asset('app-assets/vendors/js/extensions/moment.min.js') }}"></script>
    <script type="text/javascript">
        // Approve
        var vf_code = document.getElementById('vf_code');
        var vf_code_input = document.getElementById('vf_code_input');
        var btn_verification = document.getElementById('btn_verification');

        function verificationModal(id) {
            let r = (Math.random() + 1).toString(36).substring(7);
            vf_code.innerText = r;
            vf_code_input.value = "";
            $("#inlineForm").modal({backdrop: 'static', keyboard: false});
            document.getElementById('verification_form').action = "summary-approve/" + id;
        }

        function LockSummaryAndFolder(id) {
            let r = (Math.random() + 1).toString(36).substring(7);
            vf_code.innerText = r;
            $("#inlineForm").modal({backdrop: 'static', keyboard: false});
            document.getElementById('verification_form').action = "lock-summary-and-folder/" + id;
        }

        function verifyCode() {
            if (vf_code_input.value == vf_code.innerText) {
                btn_verification.disabled = false;
            } else {
                btn_verification.disabled = true;
            }
        }
        // Delete
        var vf_delete_code = document.getElementById('vf_delete_code');
        var vf_delete_code_input = document.getElementById('vf_delete_code_input');
        var btn_deletion = document.getElementById('btn_deletion');

        function deleteModal(id) {
            vf_delete_code_input.value = "";
            var route = $(".delete-btn").data('route');
            let r = (Math.random() + 1).toString(36).substring(7);
            vf_delete_code.innerText = r;
            $("#deleteForm").modal({backdrop: 'static', keyboard: false});
            document.getElementById('deletion_form').action = route;
        }

        function deleteCode() {
            if (vf_delete_code_input.value == vf_delete_code.innerText) {
                btn_deletion.disabled = false;
            } else {
                btn_deletion.disabled = true;
            }
        }
        $(document).ready(function() {
            $(".delete").on('click', function() {
                $("#danger").modal();
                route = $(this).data('route');
                document.getElementById('delete-user-form').action = route;
            });
            $(".approve").on('click', function() {
                route = $(this).data('route');
                $.ajax({
                    'url': route,
                    'type': 'post',
                    'data': {
                        '_token': '{{ csrf_token() }}',
                    },
                    'success': function(res) {
                        console.log(res)
                    },
                    'error': function(err) {
                        console.log(err)
                    }
                })
            });

            // Triggered when the summary path is clicked
            $(document).on('click', '.summary-data', function () {
                // Clear existing table data
                $('#summary-table tbody').empty();

                // Get the summary data from the data-summaries attribute
                var summaryData = $(this).data('summaries');

                // Get the encoded id from the data-encoded-id attribute
                var encodedId = $(this).data('encoded-id');

                var csvSummary = JSON.parse(summaryData.csv_summary);
                var dbSummary = JSON.parse(summaryData.db_summary);

                // Set values to HTML elements
                $('#import-detail-id').text(encodedId);
                $('#start-at').text(moment(summaryData.starts_at).format('YYYY-MM-DD'));
                $('#end-at').text(moment(summaryData.ends_at).format('YYYY-MM-DD'));
                $('#import-status').attr('class', 'badge badge-'+summaryData.status.color).text(summaryData.status.title);

                // Create a map for dbSummary using table_name as the key
                var dbSummaryMap = {};
                $.each(dbSummary, function (index, rowData) {
                    dbSummaryMap[rowData.table_name] = rowData;
                });

                // Iterate through the csvSummary and append rows to the table
                $.each(csvSummary, function (index, rowData) {
                    var dbRowData = dbSummaryMap[rowData.table_name];
                    var row = '<tr>' +
                        '<td>' + rowData.table_name + '</td>' +
                        '<td>' + rowData.total_rows + '</td>' +
                        '<td>' + rowData.column_sum + '</td>' +
                        '<td>' + (dbRowData ? dbRowData.total_rows : '-') + '</td>' +
                        '<td>' + (dbRowData ? dbRowData.column_sum : '-')+ '</td>' +
                        '</tr>';

                    // Append the row to the table body
                    $('#summary-table tbody').append(row);
                });
                // Show the modal
                $('#summary-modal').modal('show');
            });
        })
    </script>
@endSection
