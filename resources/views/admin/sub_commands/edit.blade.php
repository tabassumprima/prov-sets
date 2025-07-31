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
                            <h2 class="content-header-title float-left mb-0">Sub Command </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Accounting</a>
                                    </li>
                                    <li class="breadcrumb-item active">New journal entry
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />
                <section class="form-control-repeater">
                    <form
                        action="{{ route('lambda-sub-functions.update', ['lambda_sub_function' => $lambdaSubFunction->id]) }}"
                        method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- Invoice repeater -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group position-relative">
                                                    <label for="glcode">Select Lambda</label>

                                                    <select class="select2 form-control glcode" name="lambda_function_id">
                                                        <option disabled selected>Select Lambda</option>
                                                        @foreach ($lambdas as $lambda)
                                                            <option value={{ $lambda->id }}
                                                                {{ $lambdaSubFunction->lambdaEntries->first()->lambda_function_id == $lambda->id ? 'selected' : null }}>
                                                                {{ $lambda->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group position-relative">
                                                    <label for="voucher-date" class="required">Command</label>
                                                    <select class="select2 form-control glcode" name="command">
                                                        @if (isset($subCommands) && !empty($subCommands))
                                                            @foreach ($subCommands->lambda_sub_function_commands as $command)
                                                                <option value={{ $command->name }}
                                                                    {{ $lambdaSubFunction->command == $command->name ? 'selected' : null }}>
                                                                    {{ $command->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group position-relative">
                                                    <label for="voucher-date">Narrations</label>
                                                    <input type="text" id="narration" name="narration"
                                                        value="{{ $lambdaSubFunction->lambdaEntries->first()->narration }}"
                                                        class="form-control" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="outer-repeater">
                                            <div data-repeater-list="entries">
                                                <div data-repeater-item>
                                                    <div class="row d-flex align-items-end">
                                                        <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                                                            <div class="form-group">
                                                                <label for="glcode">GL Code</label>
                                                                <select class="select2 form-control glcode"
                                                                    name="gl_code_id">
                                                                    <option disabled selected>Select GlCode</option>

                                                                    @foreach ($glcodes as $glCode)
                                                                        <option value={{ $glCode->id }}>
                                                                            {{ $glCode->code }} - {{ $glCode->description }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 col-sm-12 col-lg-4 col-xl-4">
                                                            <div class="form-group">
                                                                <label for="glcode">Levels</label>
                                                                <select class="select2 form-control level" name="level_id">
                                                                    <option disabled selected>Select Level</option>
                                                                    @foreach ($levels as $level)
                                                                        <option value={{ $level->id }}>
                                                                            {{ $level->level }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-sm-12 col-lg-2 col-xl-2">
                                                            <div class="form-group">
                                                                <label for="credit">Transaction Type</label>
                                                                <select name="transaction_type"
                                                                    class="transaction_type select2 form-control">
                                                                    <option value="credit">Credit</option>
                                                                    <option value="debit">Debit</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-sm-12 col-lg-2 col-xl-2">
                                                            <div class="d-flex align-items-center mb-1">
                                                                <input type="radio" name="reverse_opening"
                                                                     value="true"
                                                                    class="reverse-opening">
                                                                <label class="reverse-lable" for="reverseOpening">Reverse
                                                                    Opening Balance</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-sm-12 col-lg-1 col-xl-1">
                                                            <div class="form-group">

                                                                <button class="btn btn-outline-danger text-nowrap"
                                                                    data-repeater-delete type="button">
                                                                    <i data-feather="x"></i>

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
                                                <div class="col-6 text-right">
                                                    <button class="btn btn-icon btn-primary" type="submit">
                                                        <span>Save changes</span>
                                                    </button>
                                                    <a class="btn btn-icon btn-outline-secondary" href="{{ route('lambda-sub-functions.index') }}">
                                                    <span>Cancel</span>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Invoice repeater -->
                        </div>
                    </form>
                </section>
            </div>
            <div style="display: none;" id="entries" data-entries='@json($entries)'></div>
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
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>

    <script src="{{ asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>

    {{-- <script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script> --}}
    {{-- <script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.min.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            updateUrl(`{{ Request::input('org') }}`);
            $(document).on('select2:select', '.glcode', function() {
                level = $(this).closest('.col-md-4').next().find('.level').select2();
                level_value = $(this).closest('.col-md-4').next().find('.level option:first-child').val();
                level.val(level_value).trigger('change');
            });

            $(document).on('select2:select', '.level', function() {
                level = $(this).closest('.col-md-4').prev().find('.glcode').select2();
                level_value = $(this).closest('.col-md-4').prev().find('.glcode option:first-child').val();
                level.val(level_value).trigger('change');
            });

            repeater = $('.outer-repeater').repeater({
                isFirstItemUndeletable: true,
                defaultValues: {},
                show: function() {
                    $(this).slideDown();
                    reInitSelect();

                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },
            });
            entries = $('#entries').data('entries')
            console.log(entries);

            if (entries != null) {
                if (entries.length > 0) {
                    console.log(entries)
                    repeater.setList(entries);

                }
            }
        });

        function reInitSelect() {
            $('.select2').removeClass('select2-hidden-accessible');
            $('.select2-container').remove();
            $('.glcode').select2({
                placeholder: {
                    id: '-1', // the value of the option
                    text: 'Select GlCode'
                }
            });
            $('.level').select2({
                placeholder: {
                    id: '-1', // the value of the option
                    text: 'Select Level'
                }
            });
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        }

        $("#repeater-button").click(function() {
            setTimeout(function() {

                //$(".select2").select2({
                //

                //});
                reInitSelect();

            }, 100);
        });
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })

        // Reverse opening balance checkbox
        document.addEventListener("DOMContentLoaded", function() {
            const repeaterContainer = document.querySelector('.outer-repeater');
            const checkbox = document.getElementById('reverse-opening');

            repeaterContainer.addEventListener('click', function(event) {
                const clickedCheckbox = event.target;
                if (clickedCheckbox.type === 'radio' && clickedCheckbox.classList.contains(
                        'reverse-opening')) {
                    // Get all checkboxes in the repeater
                    const checkboxes = repeaterContainer.querySelectorAll('.reverse-opening');
                    // Uncheck all checkboxes except the one that was clicked
                    checkboxes.forEach(function(checkbox) {
                        if (checkbox !== clickedCheckbox) {
                            checkbox.checked = false;
                        }
                    });
                }
            });
        });
    </script>
@endSection
