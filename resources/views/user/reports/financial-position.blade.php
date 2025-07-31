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
                        <h2 class="content-header-title float-left mb-0">Financial position statement</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Reports</a>
                                </li>
                                <li class="breadcrumb-item active">Financial position statement
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Basic tabs start -->
            <section id="basic-tabs-components">
                <div class="row match-height">
                    <!-- Basic Tabs starts -->
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="row pt-2">
                                    <div class="col-12">
                                        <h4 class="card-title text-center">Statement of financial position
                                            as at 30th June 2022</h4>
                                        <hr>
                                    </div>
                                </div>
                                <form id="report">
                                    @csrf
                                    <div class="row pb-2">
                                        <div class="col-12 col-md-4">
                                            <div class="form-group position-relative">
                                                <label for="val-date">As at</label>
                                                <input type="text" id="val-date" class="form-control pickadate-disable" placeholder="31 Dec, 2021" />
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="portfolio">Business type &nbsp;</label>
                                            <select class="select2 form-control" name="business_type[]" multiple="multiple" id="portfolio">
                                                <option value='C'>Conventional</option>
                                                <option value='T'>Takaful</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label" for="filter">Filter &nbsp;</label>
                                            <button type="button" class="btn btn-primary form-control" id="filter">Update</button>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label" for="download">Download &nbsp;</label>
                                            <button type="button" class="btn btn-outline-success form-control" id="download">
                                                <i data-feather='download'></i>
                                                <span>&nbsp; CSV</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- Striped rows start -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th class="text-right">2022</th>
                                                <th class="text-right">2021</th>

                                            </tr>
                                        </thead>
                                        <tbody id="report-body">
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Striped rows end -->
                                <!-- Striped rows end -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Basic Tabs ends -->
            </section>
        </div>
    </div>
</div>
<x-report-loader-modal route="{{route('balance.filter')}}" />

<!-- END: Content-->
@endSection

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-pickadate.min.css') }}">

@endSection

@section('scripts')

<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>

<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.min.js') }}"></script>
<script src="{{ asset('assets/js/report-fetch.js') }}"></script>



@endSection
