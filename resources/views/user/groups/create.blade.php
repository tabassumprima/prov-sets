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
                        <h2 class="content-header-title float-left mb-0">Create grouping criteria</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Group settings</a>
                                </li>
                                <li class="breadcrumb-item active">New grouping criteria
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <section class="form-control-repeater">
                <div class="row">
                    <!-- Invoice repeater -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Create IFRS 17 group mapping</h4>

                            </div>
                            <div class="card-body">
                                <form action="/group-setting/group/view" class="outer-repeater">
                                    <div data-repeater-list="outer-group" class="outer">
                                        <div data-repeater-item class="outer">
                                            <div class="row d-flex align-items-end">

                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="portfolio">Select portfolio</label>
                                                        <select class="select2 form-control" name="portfolio">
                                                            <option value='11'>Fire</option>
                                                            <option value='12'>Marine</option>
                                                            <option value='13'>Motor</option>
                                                            <option value='14'>Credit and suretyship insurance</option>
                                                            <option value='15'>Agriculture / Livestock insurance
                                                            </option>
                                                            <option value='16'>Engineering insurance</option>
                                                            <option value='17'>Travel/Assistance insurance</option>
                                                            <option value='18'>Bond insurance</option>
                                                            <option value='19'>Terrorism</option>
                                                            <option value='20'>General liability insurance</option>
                                                            <option value='21'>Workers' compensation insurance</option>
                                                            <option value='22'>Health</option>
                                                            <option value='23'>Miscellaneous</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="model">Measurement model</label>
                                                        <select class="select2 form-control" name="model">
                                                            <option value='PAA'>Premium allocation apporach</option>
                                                            <option value='GMM' disabled>General measurement model
                                                            </option>
                                                            <option value='VFA' disabled>Variable fee approach</option>
                                                            <option value='PAAE'>PAA eligible</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="business_type">Business type</label>
                                                        <select class="select2 form-control" name="business_type">
                                                            <option value='C'>Conventional</option>
                                                            <option value='T'>Takaful</option>


                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="cohort">Underwriting cohorts</label>
                                                        <select class="select2 form-control" name="cohort">
                                                            <option value='A'>Annual</option>
                                                            <option value='S'>Semi Annual</option>
                                                            <option value='Q'>Quarter</option>
                                                            <option value='M'>Month</option>

                                                        </select>
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="inner-repeater">
                                                <div data-repeater-list="inner-group" class="inner">
                                                    <div data-repeater-item class="inner">
                                                        <div class="row d-flex align-items-end ml-3">
                                                            <div class="col-md-8 col-12">
                                                                <div class="form-group">
                                                                    <label for="portfolio" class="">Select
                                                                        product</label>
                                                                    <select class="select2 form-control"
                                                                        multiple="multiple">
                                                                        @if (request()->query('portfolio') == "Ins")

                                                                        <option value='F0104'>Industrial All Risk
                                                                        </option>
                                                                        <option value='F0101'>Normal Fire</option>
                                                                        <option value='F0102'>Declaration Fire</option>
                                                                        <option value='F0103'>Loss Of Profit</option>
                                                                        <option value='F0105'>Comprehensive Machinery
                                                                            Insurance</option>

                                                                        @else
                                                                        <option value='101'>Fire 1st Surplus Treaty - TY
                                                                        </option>
                                                                        <option value='102'>Fire 2nd Surplus Treaty - TY
                                                                        </option>
                                                                        <option value='103'>Fire All Line (PRCL) - TY
                                                                        </option>
                                                                        <option value='104'>Fire Combined (ABC Re) -
                                                                            TY</option>
                                                                        <option value='107'>Fire Property Capacity (HR)
                                                                            - TY</option>


                                                                        @endif



                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-12">
                                                                <div class="form-group">
                                                                @if (request()->query('portfolio') == "Ins")
                                                                    <label for="onerous">Onerous threshold</label>
                                                                    <input type="number" class="form-control"
                                                                        placeholder="2.5"
                                                                        aria-label="Amount (to the nearest dollar)" />
                                                                @endif
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 col-12">
                                                                <div class="form-group">
                                                                    <button
                                                                        class="inner btn btn-outline-danger text-nowrap px-1"
                                                                        data-repeater-delete type="button">
                                                                        <span>x</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="row">
                                                    <div class="col-2 ml-5">
                                                        <div class="form-group">
                                                            <button
                                                                class="inner btn btn-outline-secondary text-nowrap px-1"
                                                                data-repeater-create type="button" id="repeater-button">
                                                                <span>+ new product</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-12">
                                                        <div class="form-group">
                                                            <button class="btn btn-outline-danger text-nowrap px-1"
                                                                data-repeater-delete type="button">
                                                                <i data-feather="x"></i>
                                                                <span>Delete criteria</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <hr>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <button class="outer btn btn-icon btn-outline-primary" type="button"
                                                data-repeater-create id="repeater-button1">
                                                <i data-feather="plus" class="mr-25"></i>
                                                <span>Add group</span>
                                            </button>

                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-icon btn-primary" type="submit">
                                                <span>Save changes</span>
                                            </button>
                                            <button class="btn btn-icon btn-outline-secondary" type="button">
                                                <span>Cancel</span>
                                            </button>
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
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
@endSection

@section('scripts')
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>

<script src="{{ asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>
<script>
    $(document).ready(function () {
        'use strict';

        $('.repeater').repeater({
            defaultValues: {

            },
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            }
        });

        window.outerRepeater = $('.outer-repeater').repeater({
            isFirstItemUndeletable: false,
            defaultValues: {},
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
            repeaters: [{
                isFirstItemUndeletable: false,
                selector: '.inner-repeater',
                defaultValues: { 'inner-text-input': 'inner-default' },
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            }]
        });
    });

    $("#repeater-button").click(function () {
        setTimeout(function () {

            //$(".select2").select2({
            //

            //});
            $('.select2').removeClass('select2-hidden-accessible');
            $('.select2-container').remove();
            $('.select2').select2();

        }, 100);
    });

    $("#repeater-button1").click(function () {
        setTimeout(function () {
            $('.select2').removeClass('select2-hidden-accessible');
            $('.select2-container').remove();
            $('.select2').select2();

        }, 100);
    });

</script>
@endSection
