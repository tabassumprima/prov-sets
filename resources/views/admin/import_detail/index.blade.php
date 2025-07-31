@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <x-toast :errors="$errors"/>
                <!-- users list start -->
                <section class="app-user-list">
                    <!-- list section start -->
                    <div class="card">
                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 mb-75">
                            <div class="col-lg-12 col-xl-6">
                                <h2>Import Detail Config</h2>
                            </div>
                            @if ($isBoarding)
                            <div class="col-lg-12 col-xl-6 text-right">
                                <a class="btn btn-primary" href="{{route('import-detail-configs.create')}}">Create Import Detail Config</a>
                            </div>
                            @endif
                        </div>

                        <div class="card-datatable table-responsive pt-0 pb-1 pl-1 pr-1">
                            <table class="user-list-table table data-table">
                                <thead class="thead-light">
                                    <tr>
                                       <th>Import Id</th>
                                       <th>Type</th>
                                       <th>Run By</th>
                                       <th>Start At</th>
                                       <th>End At</th>
                                       <th>Status</th>
                                       <th>Action</th>
                                    </tr>
                                </thead>
                                @foreach ($import_details as $import_detail)
                                    <tr>
                                        <td>{{ $import_detail->id }}</td>
                                        <td>{{ $import_detail->type }}</td>
                                        <td>{{ $import_detail->runBy?->name ?? 'System' }}</td>
                                        <td>{{ $import_detail->starts_at  }}</td>
                                        <td>{{ $import_detail->ends_at  }}</td>
                                        <td>{{ $import_detail->status->title  }}</td>
                                        <td>@if($import_detail->status->slug == "not-started")
                                                @if($import_detail->type != 'provision')
                                                    <a href="{{ route('import-detail-configs.edit', CustomHelper::encode($import_detail->id)) }}"
                                                    class="edit"><i data-feather="edit"></i></a>
                                                @endif

                                        @endif
                                          <a href="{{ route('import-detail-configs.show', $import_detail->id) }}"
                                            class="edit"><i data-feather="eye"></i></a>
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

                    <!-- Modal -->
                    <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel120" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myModalLabel120">Delete Provision</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete?
                                </div>
                                <form id="delete-user-form" method="post">
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
            </section>
            <!-- users list ends -->

        </div>
    </div>
    </div>
@endSection

@section('scripts')
    <script type="text/javascript">
    $(document).ready(function() {
        updateUrl(`{{ Request::input('org') }}`);     //Load road tenancy param
    });
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
    </script>
@endSection
