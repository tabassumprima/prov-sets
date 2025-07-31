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
                                <h2>Roles</h2>
                            </div>
                            <div class="col-lg-12 col-xl-6 pl-xl-75 pl-0">
                                <div
                                    class="dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex
                                    align-items-center justify-content-lg-end align-items-center flex-sm-nowrap
                                    flex-wrap mr-1">
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <a class="btn add-new btn-primary mt-50" href="{{route("roles.create")}}">
                                            <span>Add New Role</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-datatable table-responsive pt-0 pb-1 pl-1 pr-1">
                            <table class="user-list-table table data-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Users</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ Str::title($role->name) }} </td>
                                        <td> <a href="#" data-toggle="modal" data-target="#user-modal" id="user-data" onclick="userModal({{ $role->users }})">
                                            {{ $role->users()->count() }}
                                        </a> </td>
                                        <td>
                                            <a
                                                href="{{ route('roles.edit', ['role' => CustomHelper::encode($role->id)]) }}"><i
                                                    data-feather="edit"></i></a>
                                            <a data-route="{{ route('roles.destroy', [CustomHelper::encode($role->id)]) }}"
                                                class="delete"><i data-feather="trash-2" style="color:red;"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
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
                                <form id="delete-role-form" method="post">
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
                    <!-- Users Modal -->
                    <div class="modal fade modal-primary text-left" id="user-modal" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel120" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myModalLabel120">Users</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class=" table-responsive pt-0 pl-1 pr-1">
                                        <table class="user-list-table table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>User Name</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>

                                        </table>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Users Modal End -->
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
            updateUrl(`{{ Request::input('org') }}`);
        });
        $(".delete").on('click', function() {
            $("#danger").modal();
            route = $(this).data('route');
            document.getElementById('delete-role-form').action = route;
        });

        // Function to update modal content with user data
        function userModal(data) {
            const userModal = document.getElementById('user-modal');
            const userModalBody = userModal.querySelector('.modal-body');
            // Assuming data is an array of entities
            const entities = data;
            // Clear existing table rows
            userModalBody.querySelector('tbody').innerHTML = '';
            if (entities.length > 0) {
                // Append new rows with user data
                entities.forEach(user => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `<td>${user.id}</td><td>${user.name}</td><td>${user.email}</td>`;
                    userModalBody.querySelector('tbody').appendChild(newRow);
                });
            } else {
                // Display a message when there are no records
                const noRecordsRow = document.createElement('tr');
                noRecordsRow.innerHTML = '<td colspan="3" class"text-center">No records found</td>';
                userModalBody.querySelector('tbody').appendChild(noRecordsRow);
            }
        }
    </script>
@endSection
