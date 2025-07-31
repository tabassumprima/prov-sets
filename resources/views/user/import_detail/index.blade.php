@extends('user.layouts.app')
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="breadcrumb-wrapper w-100">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item">Data Import</li>
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
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100">
                                    <div class="col-6 text-left">
                                        <h2>Data Import</h2>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button class="btn add-new btn-primary" tabindex="0" type="button" data-toggle="modal" data-target="#modals-slide-in">
                                            <i data-feather='plus'></i>
                                            <span> Create Import </span>
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="main-content" class="file_manager">
                                    <div class="container">
                                        <div class="row clearfix">
                                            @if (count($imports) > 0)
                                            @foreach ($imports as $import)
                                            <div class="col-md-4 col-lg-3 col-xl-2 mt-4">
                                                <a href="{{ route('data-import.sub_imports.index', ['data_import' => CustomHelper::encode($import->id)]) }}" class="card folder-card shadow-sm text-decoration-none position-relative" style="height: 180px;">
                                                    <div class="card-body folder-card-body text-center">
                                                            @if (  $import->importDetailSummary?->status->slug == 'approved' || $import->importDetailSummary?->status->slug == 'locked')
                                                            <span class="badge position-absolute badge-danger" style="top: 10px; right: 10px;">
                                                                Locked
                                                            </span>
                                                            @endif
                                                        <div class="icon mb-2">
                                                            <i data-feather="folder" class="feather-icon text-primary" style="font-size: 32px;"></i>
                                                        </div>
                                                        <h5 class="card-title" style="font-size: 1rem;">{{ $import->name ?? 'Import File' }}</h5>
                                                        <p class="card-text" style="font-size: 0.9rem;">
                                                            <small class="text-dark">Start Date: <span class="date text-primary">{{ \Carbon\Carbon::parse($import->starts_at)->format('Y-m-d') }}</span></small>
                                                            <br />
                                                            <small class="text-dark">End Date: <span class="date text-primary">{{ \Carbon\Carbon::parse($import->ends_at)->format('Y-m-d') }}</span></small>
                                                        </p>
                                                    </div>
                                                </a>
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="col-12">
                                                <div class="card text-center shadow-sm">
                                                    <div class="card-body py-5">
                                                        <h5 class="card-title"><strong>No Record Found</strong></h5>
                                                        <p class="card-text">It seems there are no import files available at this time.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modal to add new user starts-->
            <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                <div class="modal-dialog">
                    <form class="add-new-user modal-content pt-0" method="post" action="{{ route('data-import.store')  }}">
                        @csrf
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel"> New Import Data </h5>
                        </div>
                        <div class="modal-body flex-grow-1">
                            <div class="form-group position-relative">
                                <label class="form-label required" for="group-desc">Start Date</label>
                                <input type="text" id="start-date" name="start_date" class="form-control datepicker" placeholder="31 Dec, 2021" />
                            </div>
                            <div class="form-group position-relative">
                                <label class="form-label required" for="group-desc">End Date</label>
                                <input type="text" id="end-date" name="end_date" class="form-control datepicker" placeholder="31 Dec, 2021" />
                            </div>
                            <x-form-buttons textSubmit="Submit" textCancel="Cancel" />
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal to add new user Ends-->
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
    $('.datepicker').pickadate({
        selectYears: 100,
        selectMonths: true,
        format: 'mmm dd, yyyy'
    })
</script>

<style>
    .icon:hover {
        color: #007bff;
        /* Change icon color on hover */
    }
</style>
@endSection
