@extends('user.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper w-100">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Approve Entries </li>
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
                                <h2>Approve Journal Entry</h2>
                            </div>
                        </div>

                        <div class="card-datatable table-responsive pt-0 pb-1 pl-1 pr-1">
                            <table class="user-list-table table data-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Identifier</th>
                                        <th>Type</th>
                                        <th>Entries</th>
                                        <th>Stats At</th>
                                        <th>Ends At</th>
                                        <th>Status</th>
                                        <th>Summary</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @foreach ($provisions as $provision)
                                    <tr>
                                        <td>{{ $provision->identifier }}</td>
                                        <td>{{ $provision->type }}</td>
                                        <td>{{ $provision->journal_entries_count }}</td>
                                        <td>{{ $provision->starts_at }}</td>
                                        <td>{{ $provision->ends_at }}</td>
                                        <td><span
                                                class="badge badge-{{ $provision->status->color }}">{{ $provision->status->title }}</span>
                                        </td>
                                        <td>
                                            @if ($provision->importDetailSummary)
                                            <a href="#" data-toggle="modal" data-encoded-id="{{CustomHelper::encode($provision->id)}}" data-summaries="{{$provision}}" class="summary-data">
                                                {{$provision->importDetailSummary->path}}
                                            </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('import.approve', $provision->id) }}" method="post">
                                                @csrf
                                                @authorize('approve-approve-entry', true)
                                                @if (!$provision->isLocked && Session::missing('active_provision') && $provision->status->slug == 'pending')
                                                    <button type="button" class="btn btn-success"
                                                        {{ $provision->isLocked ? 'disabled' : '' }} onclick="verificationModal({{$provision->id}})"><i data-feather="check"

                                                            style="color:rgb(255, 255, 255);"></i></button>
                                                @endif
                                                @endauthorize
                                                @authorize('delete-approve-entry', true)
                                                @if (!in_array($provision->status->slug, ['failed', 'started']))
                                                    <button type="button"
                                                        data-route="{{ route('import-detail.destroy', [$provision->id]) }}" onclick="deleteModal({{$provision->id}})"
                                                        class="btn btn-danger delete-btn"
                                                        {{ $provision->isLocked ? 'disabled' : '' }}><i
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
                        <!-- Modal to add new user starts-->
                        {{-- <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                            <div class="modal-dialog">
                                <form class="add-new-user modal-content pt-0" method="POST"
                                    action="{{ route('users.store') }}">
                                    @csrf
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Full
                                                Name</label>
                                            <input type="text" class="form-control dt-full-name"
                                                id="basic-icon-default-fullname" placeholder="John Doe" name="name"
                                                aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Phone
                                                Number</label>
                                            <input type="text" class="form-control dt-full-name"
                                                id="basic-icon-default-fullname" placeholder="+923123456" name="phone"
                                                aria-label="+923123456" aria-describedby="basic-icon-default-fullname2" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="user-role">Companies</label>
                                            <select id="company_id" name="company_id" class="select2 form-control">
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-email">Email</label>
                                            <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                                placeholder="john.doe@example.com" aria-label="john.doe@example.com"
                                                aria-describedby="basic-icon-default-email2" name="email" />
                                            <small class="form-text text-muted"> You can use letters, numbers & periods
                                            </small>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="user-role">User Role</label>
                                            <select id="user_role" name="user_role" class="select2 form-control">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role }}">{{ $role }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <x-form-buttons textSubmit="Save Changes" textCancel="Cancel" />
                                    </div>
                                </form>
                            </div>
                        </div> --}}
                        <!-- Modal to add new user Ends-->
                    </div>
                    <!-- list section end -->
                    <!-- Approved Modal -->
                    <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel33" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Approve Journal Entry</h4>
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
                                        <button type="submit" id="btn_verification" class="btn btn-primary" disabled>Approved Journal Entry</button>
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
                                    <h4 class="modal-title">Revoke Journal Entry</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="deletion_form" method="post">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <span>To avoid accidental revoke please provide additional written consent.</span>
                                        </div>

                                        <label class="required" for="accounting-year">Message</label>
                                        <div class="form-group">
                                            <textarea type="text" id="message" placeholder="Message" name="message"
                                            class="form-control" oninput="deleteCode()"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <h5><br/>Please type <i id="vf_delete_code" style="color:red;"></i> in the following text box to
                                                proceed </h5>
                                        </div>

                                        <div class="form-group">
                                            <input type="input" id="vf_delete_code_input" placeholder="Verification Code"
                                                    class="form-control" oninput="deleteCode()"/>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="btn_deletion" class="btn btn-primary" disabled>Revoke Journal Entry</button>
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
                            <h5 class="modal-title text-primary" id="summaryModalLongTitle">Import Summary<br><small class="text-secondary font-weight-bold">Import Detail Id: <span id="import-detail-id"></span></small>&nbsp;<small class="text-secondary font-weight-bold">Start at: <span id="start-at"></span></small>&nbsp;<small class="text-secondary font-weight-bold">End at: <span id="end-at"></span></small> <small>
                                <span id="import-status"></span></small></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-left pt-1  text-primary">CSV Summary</h5>
                                        <div class="table-responsive">
                                            <table class="user-list-table table" id="csv-summary-table">
                                                <thead>
                                                  <tr>
                                                    <th>Table Name</th>
                                                    <th>Total Rows</th>
                                                    <th>Sum</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <!-- Table rows will go here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="text-left pt-1  text-primary">DB Summary</h5>
                                        <div class="table-responsive">
                                            <table class="user-list-table table" id="db-summary-table">
                                                <thead>
                                                    <tr>
                                                    <th>Table Name</th>
                                                    <th>Total Rows</th>
                                                    <th>Sum</th>
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
    <script type="text/javascript">
        // Approve
        var vf_code = document.getElementById('vf_code');
        var vf_code_input = document.getElementById('vf_code_input');
        var btn_verification = document.getElementById('btn_verification');

        function verificationModal(id) {
            let r = (Math.random() + 1).toString(36).substring(7);
            vf_code.innerText = r;
            $("#inlineForm").modal({backdrop: 'static', keyboard: false});
            document.getElementById('verification_form').action = "import-approve/" + id;
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
        var message = document.getElementById('message');

        function deleteModal(id) {
            var route = $(".delete-btn").data('route');
            let r = (Math.random() + 1).toString(36).substring(7);
            vf_delete_code.innerText = r;
            $("#deleteForm").modal({backdrop: 'static', keyboard: false});
            document.getElementById('deletion_form').action = route;
        }

        function deleteCode() {
            if (vf_delete_code_input.value == vf_delete_code.innerText && message.value !="") {
                btn_deletion.disabled = false;
            } else {
                btn_deletion.disabled = true;
            }
        }
        $(document).ready(function() {


            // $(document).ready(function() {
            //     var value = 0;
            //     var id = "";
            //     var type = "";

            //     $('input[name="provision_is_locked"]:checkbox').change(
            //         function() {

            //             value = 0;
            //             if ($(this).is(':checked')) {
            //                 value = 1;
            //             } else {
            //                 value = 0;
            //             }
            //             var arr = $(this).val().split(":");
            //             type = arr[0];
            //             id = arr[1];

            //             $.ajax({
            //                 url: "/admin/users/" + id + '/status-update',
            //                 type: "POST",
            //                 data: {
            //                     id: id,
            //                     type: type,
            //                     value: value,
            //                     _token: '{{ csrf_token() }}'
            //                 },
            //                 async: true,
            //                 timeout: 6000,
            //                 dataType: "json",
            //                 success: function(data) {
            //                     location.reload();
            //                 },
            //                 error: function(xhr, textStatus, errorThrown) {
            //                     console.log(errorThrown);
            //                     return false;
            //                 }
            //             });
            //         });

            //     $('input[name="google2fa_enable"]:checkbox').change(
            //         function() {

            //             value = 0;
            //             if ($(this).is(':checked')) {
            //                 value = 1;
            //             } else {
            //                 value = 0;
            //             }
            //             var arr = $(this).val().split(":");
            //             type = arr[0];
            //             id = arr[1];

            //             $.ajax({
            //                 url: "/admin/users/" + id + '/update2fa',
            //                 type: "POST",
            //                 data: {
            //                     id: id,
            //                     type: type,
            //                     value: value,
            //                     _token: '{{ csrf_token() }}'
            //                 },
            //                 async: true,
            //                 timeout: 6000,
            //                 dataType: "json",
            //                 success: function(data) {
            //                     // console.log('success');
            //                     location.reload();
            //                 },
            //                 error: function(xhr, textStatus, errorThrown) {
            //                     console.log(errorThrown + textStatus, +xhr);
            //                     return false;
            //                 }
            //             });
            //         });
            // });

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
                // document.getElementById('delete-user-form').action = route;
            });

            // Triggered when the summary path is clicked
            $(document).on('click', '.summary-data', function () {
                // Clear existing table data
                $('#csv-summary-table tbody').empty();

                // Get the summary data from the data-summaries attribute
                var summaryData = $(this).data('summaries');

                // Get the encoded id from the data-encoded-id attribute
                var encodedId = $(this).data('encoded-id');

                var importDetail = summaryData.import_detail_summary;
                var csvSummary = JSON.parse(importDetail.csv_summary);
                var dbSummary = JSON.parse(importDetail.db_summary);

                // Set values to HTML elements
                $('#import-detail-id').text((encodedId));
                $('#start-at').text(summaryData.starts_at);
                $('#end-at').text(summaryData.ends_at);
                $('#import-status').attr('class', 'badge badge-'+summaryData.status.color).text(summaryData.status.title);

                // Iterate through the data and append rows to the table
                $.each(csvSummary, function (index, rowData) {
                    var row = '<tr>' +
                        '<td>' + rowData.table_name + '</td>' +
                        '<td>' + rowData.total_rows + '</td>' +
                        '<td>' + rowData.column_sum + '</td>' +
                        '</tr>';

                    // Append the row to the table body
                    $('#csv-summary-table tbody').append(row);
                });

                // Clear existing table data
                $('#db-summary-table tbody').empty();

                // Iterate through the data and append rows to the table
                $.each(dbSummary, function (index, rowData) {
                    var row = '<tr>' +
                        '<td>' + rowData.table_name + '</td>' +
                        '<td>' + rowData.total_rows + '</td>' +
                        '<td>' + rowData.column_sum + '</td>' +
                        '</tr>';

                    // Append the row to the table body
                    $('#db-summary-table tbody').append(row);
                });

                // Show the modal
                $('#summary-modal').modal('show');
            });
        })
    </script>
@endSection
