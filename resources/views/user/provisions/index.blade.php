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
                    <div id="main-content" class="file_manager">
                        <div class="container">
                            <div class="row clearfix">
                                @if (count($provisions) > 0)
                                    @foreach ($provisions as $provision)
                                        <div class="col-md-3 col-xl-3 col-lg-3 col-sm-12">
                                            <div class="card">
                                                <div class="file">
                                                    <a href="{{ route('provisions.show', ['provision' => CustomHelper::encode($provision->import_detail_id)]) }}">
                                                        <div class="icon">
                                                            <i data-feather="folder" class="feather-icon text-primary"></i>
                                                        </div>
                                                        <div class="file-name">
                                                            <p class="text-muted" style="display:none;">{{ CustomHelper::encode($provision->import_detail_id) }}</p>
                                                            <small class="text-muted">Valuation Date <span class="date text-primary">{{ $provision->valuation_date }}</span></small>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="text-center py-2">
                                                <i> <strong>No Record Found</strong></i>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
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
