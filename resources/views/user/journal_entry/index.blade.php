@extends('user.layouts.app')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Approve Entry</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Accounting</li>
                                    <li class="breadcrumb-item active">Approve Journal Entry
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />
                <section class="form-control-repeater">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Run By</th>
                                                    <th>Type</th>
                                                    <th>Starts At</th>
                                                    <th>Ends At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($import_details as $import_detail)
                                                    <tr>
                                                        <td>{{ $import_detail->runBy->name }}</td>
                                                        <td>{{ Str::title($import_detail->type) }}</td>
                                                        <td>{{ $import_detail->starts_at }}</td>
                                                        <td>{{ $import_detail->ends_at }}</td>
                                                        <td>
                                                            @authorize('approve-journal-entry', true)
                                                            <button
                                                                data-route="{{ route('import.approve', ['import' => $import_detail->id]) }}"
                                                                class="btn btn-success journal-approve"
                                                                data-id="{{ $import_detail->id }}"
                                                                {{ $import_detail->approved_by ? 'disabled' : '' }}>Approve</button>
                                                            @endauthorize
                                                            @authorize('delete-journal-entry', true)
                                                            @if (!in_array($import_detail->status->slug, ['failed', 'started']))
                                                                <button type="button"
                                                                    data-route="{{ route('import-detail.destroy', [$import_detail->id]) }}"
                                                                    class="btn btn-danger delete"
                                                                    {{ $import_detail->isLocked ? 'disabled' : '' }}><i
                                                                        data-feather="trash-2"
                                                                        style="color:rgb(255, 255, 255);"></i></button>
                                                            @endif
                                                            @endauthorize
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>

                                        <div class="col-12">
                                            <nav aria-label="Page navigation example">
                                                <ul class="pagination mt-2 justify-content-center">
                                                    <li class="page-item prev"><a class="page-link"
                                                            href="javascript:void(0);">Prev</a></li>
                                                    <li class="page-item active"><a class="page-link"
                                                            href="javascript:void(0);">1</a></li>

                                                    <li class="page-item next"><a class="page-link"
                                                            href="javascript:void(0);">Next</a></li>
                                                </ul>
                                            </nav>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- /Invoice repeater -->
                        </div>

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
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endSection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css') }}">
@endSection

@section('scripts')
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>







    <script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.journal-approve').click(function() {
                var route = $(this).data('route');
                var id = $(this).data('id');
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        location.reload();
                    }
                });
            });

            $(".delete").on('click', function() {
                $("#danger").modal();
                route = $(this).data('route');
                document.getElementById('delete-user-form').action = route;
            });
        });
    </script>
@endSection
