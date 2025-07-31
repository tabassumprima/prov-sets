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
                                    <li class="breadcrumb-item">System Provision
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('provision-setting.index') }}">Provision Setting</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('provision-setting.mappings.index', ['provision_setting' => CustomHelper::encode($provisionSetting->id)]) }}">Select Provision Mapping</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-toast :errors="$errors" />

            @if (!$isBoarding)
                @if ($provisionSetting->status->slug == 'started')
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-body">
                            Disable the Provision Setting to edit the mapping.
                        </div>
                    </div>
                @endif
            @endif

            <div class="content-body">
                <section class="form-control-repeater">
                    <div class="row">
                        <!-- Invoice repeater -->
                        <div class="col-12">
                            <div class="card" style="min-height: 66vh">
                                <h2 class="pl-2 pt-1">Provision Mapping (Treaty)</h2>
                                <h4 class="pl-2">Provision Mapping ({{ $provisionSetting->name }})</h4>
                                <div class="card-body d-flex flex-column">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="spreadsheet" data-department='@json($provisionMappingProducts['products'])'></div>
                                        </div>
                                    </div>
                                    <div class="row w-100  mt-auto">
                                        <div class="col-12 text-right pt-1">
                                            <button id="save" class="btn btn-primary" type="button" {{ !$isBoarding && ($provisionSetting->status->slug == 'started') ? 'disabled' : '' }}>
                                                <span> Save Mapping</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice repeater -->
                    </div>
                </section>
                <div class="toast toast-basic hide position-fixed" role="alert" aria-live="assertive" aria-atomic="true"
                    data-delay="5000" style="top: 1rem; right: 1rem">
                    <div class="toast-header">
                        <img src="../../../app-assets/images/logo/logo.png" class="mr-1" alt="Toast image" height="18"
                            width="25" />
                        <strong class="mr-auto">Vue Admin</strong>
                        <small class="text-muted">11 mins ago</small>
                        <button type="button" class="ml-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">Hello, world! This is a toast message. Hope you're doing well.. :)</div>
                </div>
                <div style="display: none;" id="discount_rates" data-discount_rates='@json($provisionMappingProducts['discount_rates'])'></div>
                <div style="display: none;" id="ibnr" data-ibnr='@json($provisionMappingProducts['ibnrAssumptions'])'></div>
                <div style="display: none;" id="risk_adjustments" data-risk_adjustments='@json($provisionMappingProducts['riskAdjustments'])'>
                </div>
                <div style="display: none;" id="claim_patterns" data-claim_patterns='@json($provisionMappingProducts['claimPatterns'])'></div>
                <div style="display: none;" id="route"
                    data-route="{{ route('provision.treaty.store', $provisionSetting->id) }}"
                    data-token="{{ csrf_token() }}"></div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endSection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/css/jsuites.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/jspreadsheet.css') }}" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
@endSection

@section('scripts')
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('assets/js/jspreadsheet.js') }}"></script>
    <script src="{{ asset('assets/js/jsuites.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/jexcel-provision-treaty.js') }}"></script>
    <script>
        $(window).on('load', function() {
            compactMenu = true;
            $.app.menu.init(compactMenu);
        });
    </script>
@endSection
