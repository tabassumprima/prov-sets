@extends('user.layouts.app')
@section('content')
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
                                    <li class="breadcrumb-item active"> <a href="{{ route('discount-rates.index') }}">Discount Rates</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="{{ route('discount-rates.files.index', ['discount_rate' => CustomHelper::encode($record->file_id)]) }}">Disocunt Rate File Detail</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <x-toast :errors="$errors" type="discount_rate" />

                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-header">
                            <div class="row w-100">
                                <div class="col-12 text-left">
                                    <h2>Edit Discount Rate File Information</h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <!-- IBNR edit -->
                                    <form class="form-validate" method="POST"
                                        action="{{ route('discount-rates.files.update', ['discount_rate' => CustomHelper::encode($record->file_id), 'file' => CustomHelper::encode($record->id)])}}"
                                        autocomplete="off" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="name" class="required">Name</label>
                                            <input type="text" class="form-control" placeholder="Name"
                                            name="name" id="name" value="{{$record->name}}"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="discount_file" class="required">Choose File</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="discount_file" name="discount_file">
                                                <label class="custom-file-label" for="discount_file">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="form-group position-relative">
                                            <label class="form-label required" for="valuation_date">Valuation date</label>
                                            <input type="text" id="valuation_date" name="valuation_date" class="form-control flatpickr-basic"
                                                placeholder="31 Dec, 2021"  value="{{$record->valuation_date}}"/>
                                        </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <x-form-buttons textSubmit='Save Changes' />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
        </div>
    </div>
@endSection
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css') }}">
@endSection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
@endSection
