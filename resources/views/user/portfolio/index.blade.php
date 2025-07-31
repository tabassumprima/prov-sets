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
                                    @if (Request::is('portfolio/insurance'))
                                        <li class="breadcrumb-item">Insurance</li>
                                    @else
                                        <li class="breadcrumb-item">Reinsurance</li>
                                    @endif
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
                                    <div class="row w-100">
                                        <div class="col-6 text-left">
                                            <h2>Portfolio Mapping ({{ Str::title($type) }})</h2>
                                        </div>
                                        <div class="col-6 text-right">
                                            @authorize('create-'.$type.'-portfolio',true)
                                            <button class="btn add-new btn-primary" tabindex="0" type="button"
                                                data-toggle="modal" data-target="#modals-slide-in"><i
                                                    data-feather='plus'></i><span> Add new portfolio</span></button>
                                            @endauthorize
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive pl-1 pr-1">
                                        <table class="table data-table">
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Portfolio name</th>
                                                    <th>Portfolio shortcode</th>
                                                    <th>Type</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($portfolios as $portfolio)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $portfolio->name }}</td>
                                                        <td>{{ $portfolio->shortcode }}</td>
                                                        <td>{{ Str::title($portfolio->type) }}</td>
                                                        <td>
                                                            @authorize('update-'.$type.'-portfolio',true)
                                                            <a href="{{ route('portfolios.edit', CustomHelper::encode($portfolio->id)) }}"
                                                                class="edit"><i data-feather="edit"></i></a>
                                                            @endauthorize
                                                            @authorize('delete-'.$type.'-portfolio',true)
                                                            <a data-route="{{ route('portfolios.destroy', CustomHelper::encode($portfolio->id)) }}"
                                                                class="delete"><i data-feather="trash"
                                                                    style="color:red;"></i></a>
                                                            @endauthorize
                                                        </td>
                                                    </tr>
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
                                action="{{ route('portfolios.store') }}">
                                @csrf
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New {{ $type }} portfolio</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="form-group">
                                        <label class="form-label required" for="group-name">Portfolio name</label>
                                        <input type="text" class="form-control" id="group-name"
                                            placeholder="New-portfolio-1" name="name" maxlength="100" aria-label="group-name" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label required" for="group-desc">Portfolio shortcode</label>
                                        <input type="text" id="group-desc" class="form-control"
                                            placeholder="This is the new portfolio set" maxlength="6"  aria-label="group-desc"
                                            name="shortcode" />
                                    </div>
                                    <input class="form-control" id="type" name="type" value={{ $type }}
                                        hidden>
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
        $(".delete").on('click', function() {
            $("#danger").modal();
            route = $(this).data('route');
            document.getElementById('delete-user-form').action = route;
        });
    </script>
@endSection
