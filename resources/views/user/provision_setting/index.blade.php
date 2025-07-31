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

                <!-- Basic tabs start -->
                <section id="basic-tabs-components">
                    <div class="row match-height">
                        <!-- Basic Tabs starts -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    @authorize('create-provision-setting', true)
                                    <div class="row w-100">
                                        <div class="col-6 text-left">
                                            <h2>Provision Setting</h2>
                                        </div>
                                        <div class="col-6 text-right">
                                            <button class="btn add-new btn-primary" tabindex="0" type="button"
                                                data-toggle="modal" data-target="#modals-slide-in"
                                                {{ Session::has('active_provision') ? 'disabled' : '' }}><i
                                                    data-feather='plus'></i><span> Add new Provision</span></button>

                                        </div>
                                    </div>
                                    @endauthorize
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive pl-1 pr-1">
                                        <table class="table data-table">
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Provision name</th>
                                                    <th>Provision description</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($provisionSettings as $provision_setting)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <a href="{{ route('provision-setting.mappings.index', ['provision_setting' => CustomHelper::encode($provision_setting->id)]) }}"
                                                                class="edit"> {{ $provision_setting->name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $provision_setting->description }}</td>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $provision_setting->status->color }}">{{ $provision_setting->status->slug }}</span>
                                                        </td>
                                                        <td>
                                                            @authorize('delete-provision-setting', true)
                                                            <a data-route="{{ route('provision-setting.destroy', CustomHelper::encode($provision_setting->id)) }}"
                                                                class="delete"><i data-feather="trash"
                                                                    style="color:red;"></i></a>
                                                            @endauthorize
                                                            @authorize('status-update-provision-setting', true)
                                                            <button
                                                                id='update_file:{{ CustomHelper::encode($provision_setting->id) }}'
                                                                name="update_file"
                                                                data-status="{{ $provision_setting->status->slug }}"
                                                                type="button"
                                                                class="btn btn-sm btn-outline-{{ $provision_setting->status->slug == 'started' ? 'danger' : 'success' }} waves-effect">{{ $provision_setting->status->slug == 'started' ? 'Disable' : 'Enable' }}</button>
                                                            @endauthorize
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Basic Tabs ends -->
                    </div>
                    <!-- Modal to add new user starts-->
                    <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                        <div class="modal-dialog">
                            <form class="add-new-user modal-content pt-0" method="post"
                                action="{{ route('provision-setting.store') }}">
                                @csrf
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Provision</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="form-group">
                                        <label class="form-label required" for="group-name">Provision name</label>
                                        <input type="text" class="form-control" id="group-name"
                                            placeholder="New-Provision-1" name="name" aria-label="group-name" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label required" for="group-desc">Provision description</label>
                                        <input type="text" id="group-desc" class="form-control"
                                            placeholder="This is the new Provision set" aria-label="group-desc"
                                            name="description" />
                                    </div>
                                    <x-form-buttons textSubmit="Submit" textCancel="Cancel" />
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Modal to add new user Ends-->
                </section>
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
        </div>
    </div>
    <!-- END: Content-->
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
    <script>
        $('button[name="update_file"]').click(function() {
            var id = $(this).attr('id').split(':')[1];
            var value = $(this).data('status');
            var url = '{{ route('provision.status', ['provision' => ':id']) }}';
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    value: value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    location.reload();
                },
                error: function(err) {
                    console.log(err);
                }
            })
        })
        $(".delete").on('click', function() {
            $("#danger").modal();
            route = $(this).data('route');
            document.getElementById('delete-user-form').action = route;
        });
    </script>
@endSection
