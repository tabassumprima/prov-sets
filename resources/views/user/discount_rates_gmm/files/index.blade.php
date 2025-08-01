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
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">Actuarial Assumption</li>
                                <li class="breadcrumb-item"><a href="{{ route('discount-rates.index') }}">GMM Inputs</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <x-toast :errors="$errors" type="discount_rate"/>


            <!-- Basic tabs start -->
            <section id="basic-tabs-components">
                <div class="row match-height">
                    <!-- Basic Tabs starts -->


                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100">
                                    <div class="col-md-6 text-left">
                                        <h2>GMM Inputs</h2>
                                    </div>
                                    @authorize('create-discount-rate-file', true)
                                    <div class="col-md-6 text-right">

                                        <button class="btn add-new btn-primary" tabindex="0" type="button"
                                            data-toggle="modal" data-target="#modals-slide-in"><i
                                                data-feather='plus'></i><span> Upload new</span></button>
                                    </div>
                                    @endauthorize
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <th>Name</th>
                                                <th>Uploaded file</th>
                                                <th>Valuation date</th>
                                                <th>Upload date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td><span class="font-weight-bold">GMM Inputs for year end 2022</span></td>
                                                <td><a href="{{route('discount_rate.file', CustomHelper::encode(1))}}">{{'GMM Inputs 2022_1752133049.csv'}}</a></td>
                                                <td>2024-12-31</td>
                                                <td>2025-01-01</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#">
                                                                <i data-feather="edit-2" class="mr-50"></i>
                                                                <span>Edit</span>
                                                            </a>
                                                            <a class="dropdown-item delete" href="#">
                                                                <i data-feather="trash" class="mr-50"></i>
                                                                <span>Delete</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>2</td>
                                                <td><span class="font-weight-bold">GMM Inputs for year end 2023</span></td>
                                                 <td><a href="{{route('discount_rate.file', CustomHelper::encode(1))}}">{{'GMM Inputs 2023_1752133049.csv'}}</a></td>
                                                <td>2024-11-30</td>
                                                <td>2025-01-02</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#">
                                                                <i data-feather="edit-2" class="mr-50"></i>
                                                                <span>Edit</span>
                                                            </a>
                                                            <a class="dropdown-item delete" href="#">
                                                                <i data-feather="trash" class="mr-50"></i>
                                                                <span>Delete</span>
                                                            </a>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
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
                 
                </div>
                <!-- Modal to add new user Ends-->
                <!-- Modal -->
                <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel120" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel120">Delete IBNR File</h5>
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
            </section>

        </div>
    </div>
</div>
<!-- END: Content-->
@endSection

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets/css/plugins/forms/pickers/form-pickadate.min.css') }}">
@endSection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.datepicker').pickadate({
                selectYears: 100,
                selectMonths: true,
                format: 'mmm dd, yyyy'
        });

        $(".delete").on('click', function() {
            $("#danger").modal();
            route = $(this).data('route');
            document.getElementById('delete-user-form').action = route;
        });
    })
</script>

@endSection
