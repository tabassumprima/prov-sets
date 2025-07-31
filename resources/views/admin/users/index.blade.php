@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />
                <!-- users list start -->
                <section class="app-user-list">
                    <!-- list section start -->
                    <div class="card">
                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 mb-75">
                            <div class="col-lg-12 col-xl-6">
                                <h2>User Information</h2>
                            </div>
                            <div class="col-lg-12 col-xl-6 pl-xl-75 pl-0">
                                <div
                                    class="dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex
                                    align-items-center justify-content-lg-end align-items-center flex-sm-nowrap
                                    flex-wrap mr-1">
                                    <div class="dt-buttons btn-group flex-wrap">
                                        @authorize('manage user')
                                            <button class="btn add-new btn-primary mt-50" tabindex="0"
                                                aria-controls="DataTables_Table_0" type="button" data-toggle="modal"
                                                data-target="#modals-slide-in">
                                                <span>Add New User</span>
                                            </button>
                                        @endauthorize
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-datatable table-responsive pt-0 pb-1 pb-1 pl-1 pr-1">
                            <table class="user-list-table table data-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->roles->pluck('name')->first() }}</td>
                                        <td>
                                            <div class="custom-control custom-control-success custom-checkbox">
                                                <input type="checkbox" name="user_is_active" class="custom-control-input"
                                                    id="user_is_active:{{ CustomHelper::encode($user->id) }}"
                                                    value="user_is_active:{{ CustomHelper::encode($user->id) }}"
                                                    {{ $user->is_active == '1' ? 'checked' : '' }}
                                                    {{ $user->email == Auth::user()->email ? '' : '' }} />
                                                <label class="custom-control-label"
                                                    for="user_is_active:{{ CustomHelper::encode($user->id) }}">Enabled</label>
                                            </div>
                                        </td>
                                        <td>
                                            @if (!$user->hasRole('admin'))
                                                <a href="{{ route('users.impersonate', ['user' => CustomHelper::encode($user->id)]) }}"
                                                    class="btn btn-warning btn-sm">Login</a>
                                            @endif
                                            <a id="user_edit:{{ $user->id }}"
                                                href="{{ route('users.edit', ['user' => CustomHelper::encode($user->id)]) }}"><i
                                                    data-feather="edit"></i></a>
                                            <a data-route="{{ route('users.destroy', [CustomHelper::encode($user->id)]) }}"
                                                class="delete"><i data-feather="trash-2" style="color:red;"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <!-- Modal to add new user starts-->
                        <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                            <div class="modal-dialog">
                                <form class="add-new-user modal-content pt-0" method="POST"
                                    action="{{ route('users.store') }}">
                                    @csrf
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Full
                                                Name</label>
                                            <input type="text" class="form-control dt-full-name"
                                                id="basic-icon-default-fullname" placeholder="John Doe" name="name"
                                                aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" required />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-fullname">Phone
                                                Number</label>
                                            <input minlength="7" type="text" class="form-control dt-full-name"
                                                id="basic-icon-default-fullname" placeholder="923123456" name="phone"
                                                aria-label="923123456" aria-describedby="basic-icon-default-fullname2" required />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="basic-icon-default-email">Email</label>
                                            <input type="text" id="basic-icon-default-email"
                                                class="form-control dt-email" placeholder="john.doe@example.com"
                                                aria-label="john.doe@example.com"
                                                aria-describedby="basic-icon-default-email2" name="email" required/>
                                            <small class="form-text text-muted"> You can use letters, numbers & periods
                                            </small>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="user-role">User Role</label>
                                            <select id="user_role" name="user_role" class="select2 form-control" required>
                                                <option selected value="">Select Role</option>
                                                @foreach ($roles as $id => $role)
                                                    <option value="{{ $id }}">{{ $role }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label required" for="user-role">User Verification Type</label>
                                            <select id="verification_type" name="verification_type" class="select2 form-control" required>
                                                <option value="email">Email</option>
                                                <option value="2fa">2fa</option>
                                            </select>
                                        </div>
                                        <x-form-buttons textSubmit="Save Changes" textCancel="Cancel" />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Modal to add new user Ends-->
                    </div>
                    <!-- list section end -->

                    <!-- Modal -->
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

            $('#modals-slide-in').on('hidden.bs.modal', function (event) {
                $(this).find('input[type="text"], input[type="number"], textarea, select').val('');
            });

            //Load road tenancy param
            updateUrl(`{{ Request::input('org') }}`);
            var value = 0;
            var id = "";
            var type = "";

            $('input[name="user_is_active"]:checkbox').change(
                function() {

                    value = 0;
                    if ($(this).is(':checked')) {
                        value = 1;
                    } else {
                        value = 0;
                    }
                    var arr = $(this).val().split(":");
                    type = arr[0];
                    id = arr[1];

                    $.ajax({
                        url: "/admin/users/" + id + '/status-update',
                        type: "POST",
                        data: {
                            id: id,
                            type: type,
                            value: value,
                            _token: '{{ csrf_token() }}'
                        },
                        async: true,
                        timeout: 6000,
                        dataType: "json",
                        success: function(data) {
                            location.reload();
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log(errorThrown);
                            return false;
                        }
                    });
                });

        });

        $(".delete").on('click', function() {
            $("#danger").modal();
            route = $(this).data('route');
            document.getElementById('delete-user-form').action = route;
        });
    </script>
@endSection
