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
                                @if ($group->applicable_to == 'insurance')
                                    <li class="breadcrumb-item">Insurance</li>
                                @else
                                    <li class="breadcrumb-item">Reinsurance</li>
                                @endif
                                <li class="breadcrumb-item"><a href="{{ route('group.index', ['type' => $group->applicable_to]) }}">Groups</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-toast :errors="$errors" />
        @if (($group->status->slug == 'started' || $group->status->slug == 'expired') && !$group->organization->isBoarding)
        <div class="alert alert-danger" role="alert">
            <div class="alert-body">
                {{ trans('user/group.error_active') }}
            </div>
        </div>
        @endif
        <div class="content-body">
            <section class="form-control-repeater">
                <div class="row">
                    <!-- Invoice repeater -->
                    <div class="col-12">
                        <div class="card" style="min-height: 66vh">
                            <h2 class="pt-1 pl-2">Product Mapping (Insurance)</h2>
                            <h5 class="pl-2">Create IFRS 17 Product</h5>
                            <div class="card-body d-flex flex-column">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="spreadsheet" data-department='@json($departments["product"])'></div>
                                    </div>
                                </div>
                                <div class="row w-100  mt-auto">
                                    <div class="col-12 text-right pt-1">
                                        <button id="save" class="btn btn-{{ ($group->status->slug == 'started' || $group->status->slug == 'expired') && !$group->organization->isBoarding ? 'secondary' : 'primary' }}" type="button" {{ ($group->status->slug == 'started' || $group->status->slug == 'expired') && !$group->organization->isBoarding ? 'disabled' : '' }}>
                                            <span> Save Mapping</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice repeater -->
                </div>
            </section>
            <div class="toast toast-basic hide position-fixed" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="top: 1rem; right: 1rem">
                    <div class="toast-header">
                        <img src="../../../app-assets/images/logo/logo.png" class="mr-1" alt="Toast image" height="18" width="25" />
                        <strong class="mr-auto">Vue Admin</strong>
                        <small class="text-muted">11 mins ago</small>
                        <button type="button" class="ml-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">Hello, world! This is a toast message. Hope you're doing well.. :)</div>
                </div>
            <div style="display: none;" id="cohorts" data-cohorts='@json($departments["cohorts"])'></div>
            <div style="display: none;" id="measurement" data-measurement='@json($departments["measurement"])'></div>
            <div style="display: none;" id="grouping" data-grouping='@json($departments["grouping"])'></div>
            <div style="display: none;" id="portfolios" data-portfolios='@json($departments["portfolios"])'></div>
            <div style="display: none;" id="route" data-route="{{ route('groups.products.store', $group->id) }}" data-token="{{  csrf_token() }}"></div>
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
<script src="{{ asset('assets/js/jexcel-group.js') }}"> </script>
<script>
    $(window).on('load', function () {
        compactMenu = true;
       $.app.menu.init(compactMenu);
    });
</script>
@endSection
