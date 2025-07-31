@extends('admin.layouts.app')

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
                            <h2 class="content-header-title float-left mb-0">Portfolio Criteria</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a
                                            href="{{ route('criterias.index') }}">Portfolio Criteria</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <x-toast :errors="$errors" />
            <div class="content-body">
                <section class="form-control-repeater">
                    <div class="row">
                        <!-- Invoice repeater -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Create IFRS 17 portfolios</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('portfolio.update', CustomHelper::encode($portfolio->id)) }}"
                                        method="post" class="outer-repeater">
                                        @csrf
                                        @method('PUT')
                                        <div data-repeater-list="invoice">
                                            <div data-repeater-item>
                                                <div class="row d-flex align-items-end">
                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label class="required" for="itemname">Portfolio
                                                                name</label>
                                                            <input type="text" class="form-control" id="portfolio-name"
                                                                aria-describedby="portfolio-name" name="name"
                                                                value="{{ $portfolio->name }}"
                                                                placeholder="Enter portfolio name" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label class="required" for="itemname">Portfolio
                                                                description</label>
                                                            <input type="text" class="form-control" id="portfolio-name"
                                                                aria-describedby="portfolio-name" name="description"
                                                                value="{{ $portfolio->description }}"
                                                                placeholder="Enter portfolio name" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <label>System department</label>
                                                        <div class="form-group">
                                                            <select class="select2 form-control"
                                                                name="system_department_id[]" id="system_department"
                                                                multiple="multiple">
                                                                @foreach ($departments as $department)
                                                                    <option value="{{ $department->id }}"
                                                                        {{ $portfolio->systemDepartments->contains($department) ? 'selected="selected"' : '' }}>
                                                                        {{ $department->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <label>Status</label>
                                                        <div class="form-group">
                                                            <select class="select2 form-control" name="is_active">
                                                                <option value="1"
                                                                    {{ $portfolio->is_active == 1 ? 'selected="selected"' : '' }}>
                                                                    Enabled</option>
                                                                <option value="0"
                                                                    {{ $portfolio->is_active == 0 ? 'selected="selected"' : '' }}>
                                                                    Disabled</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-12 mb-50">
                                                        <div class="form-group">
                                                            <button class="btn btn-outline-danger text-nowrap px-1"
                                                                data-repeater-delete id="delete-system" type="button">
                                                                <i data-feather="x" class="mr-25"></i>
                                                                <span>Delete</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <button class="btn btn-icon btn-outline-primary" type="button"
                                                    data-repeater-create id="repeater-button">
                                                    <i data-feather="plus" class="mr-25"></i>
                                                    <span>Add New</span>
                                                </button>

                                            </div>
                                            <div class="col-6">
                                                <x-form-buttons textSubmit="Update" textCancel="Cancel" />
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice repeater -->
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endSection

@section('page-css')
@endSection

@section('scripts')
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>
@endSection
