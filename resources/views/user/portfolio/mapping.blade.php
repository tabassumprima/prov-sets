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
                                    @if ($criteria->applicable_to == 'insurance')
                                        <li class="breadcrumb-item">Insurance</li>
                                    @else
                                        <li class="breadcrumb-item">Reinsurance</li>
                                    @endif
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('criteria.index', ['type' => $criteria->applicable_to]) }}">Portfolio
                                            Criteria</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-toast :errors="$errors" />

            @if (!$isBoarding)
                @if ($criteria->status->slug == 'started' || $criteria->status->slug == 'expired')
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-body">
                            {{ trans('user/criteria.error_active') }}
                        </div>
                    </div>
                @endif
            @endif
            </div>
            <div class="content-body">
                <section class="form-control-repeater">
                    <div class="row">
                        <!-- Invoice repeater -->
                        <div class="col-12">
                            <div class="card" style="min-height: 66vh">
                                <h2 class="pt-1 pl-1">Portfolio Mapping</h2>
                                <h5 class="pl-1">Create IFRS 17 Portfolios</h5>
                                <div class="card-body d-flex">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="spreadsheet" data-department='@json($departments)'
                                                    data-portfolios='@json($portfolios)'></div>
                                            </div>
                                        </div>
                                        <div class="row w-100 mt-auto">
                                            <div class="col-12 text-right pt-1">
                                                <button id="save"
                                                    class="btn btn-{{ !$isBoarding && ($criteria->status->slug == 'started' || $criteria->status->slug == 'expired') ? 'secondary' : 'primary' }}"
                                                    type="button"
                                                    {{ !$isBoarding && ($criteria->status->slug == 'started' || $criteria->status->slug == 'expired') ? 'disabled' : '' }}>
                                                    <span>Save Mapping</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice repeater -->
                    </div>
                </section>
                <div style="display: none;" id="route" data-route="{{ route('portfolio.saveMapping', $criteria->id) }}"
                    data-token="{{ csrf_token() }}" data-refresh="{{ route('criteria.index', ['type' => $criteria->applicable_to]) }}"></div>
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
    <script src="{{ asset('assets/js/jexcel-portfolio.js') }}"></script>
@endSection
