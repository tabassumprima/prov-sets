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
                            <h2 class="content-header-title float-left mb-0">New Journal Entry</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Accounting
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
                    <form action="{{ route('journal-entries.store') }}" method="post" id="repeater-form">
                        @csrf
                        <div class="row">
                            <!-- Invoice repeater -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="required" for="voucher-type">Voucher type</label>
                                                    <select class="select2 form-control " name="voucher_type_id" id="voucher_type_id">
                                                        @foreach ($voucherTypes as $voucherType)
                                                            <option value={{ $voucherType->id }}>
                                                                {{ $voucherType->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="required" for="accounting-year">Accounting year</label>
                                                    <select class="select2 form-control" name="accounting_year_id"
                                                        id="accounting-year">
                                                        @foreach ($accountingYears as $accountingYear)
                                                            <option value={{ $accountingYear->id }}>
                                                                {{ $accountingYear->year }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="required" for="branch">Branch</label>
                                                    <select class="select2 form-control" name="branch_info_id"
                                                        id="branch">
                                                        @foreach ($branchInfos as $branchInfo)
                                                            <option value={{ $branchInfo->id }}>
                                                                {{ $branchInfo->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="required" for="business-type">Business type</label>
                                                    <select class="select2 form-control" name="business_type_id"
                                                        id="business-type">
                                                        @foreach ($businessTypes as $businessType)
                                                            <option value={{ $businessType->id }}>
                                                                {{ $businessType->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-group position-relative">
                                                    <label class="required" for="voucher-date">Voucher date</label>
                                                    <input type="text" id="voucher-date" name="system_date"
                                                        value="{{ old('system_date') }}" class="form-control datepicker" />
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-group position-relative">
                                                    <label class="required" for="voucher-date">Narration</label>
                                                    <input type="text" id="narration" name="system_narration1"
                                                        value="{{ old('system_narration1') }}" class="form-control" />
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
                                                    <div class="row d-flex align-items-end repeater-select">
                                                        <div class="col-md-4 col-12 gl-code-div">
                                                            <div class="form-group">
                                                                <label class="required" for="glcode">GL Code</label>
                                                                <select class="select2 form-control gl_code_id"
                                                                    name="gl_code_id">
                                                                    <option disabled selected>Select GLCode</option>
                                                                    @foreach ($glCodes as $glCode)
                                                                        <option data-value="{{$glCode->chartOfAccount->category}}" value={{ $glCode->id }}>
                                                                            {{ $glCode->code }} -
                                                                            {{ $glCode->description }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-12 system_department_div">
                                                            <div class="form-group">
                                                                <label class="required" for="system_department">System Departments</label>
                                                                <select class="select2 form-control system_department_id"
                                                                    name="system_department_id">
                                                                    <option disabled selected>Select System Department</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-4 portfolio-div">
                                                            <div class="form-group">
                                                                <label class="required" for="portfolio">Portfolio</label>
                                                                <select class="select2 form-control portfolio"
                                                                    name="portfolio_id">

                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                            <div class="form-group">
                                                                <label class="required" for="debit">Debit</label>
                                                                <input type="text" class="form-control debit"
                                                                    id="debit" aria-describedby="debit" name="debit"
                                                                    placeholder="1151.0" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-12">
                                                            <div class="form-group">
                                                                <label class="required" for="credit">Credit</label>
                                                                <input type="text" class="form-control credit"
                                                                    id="credit" aria-describedby="credit" name="credit"
                                                                    placeholder="0.0" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                            <label class="required">Policy reference</label>
                                                            <div class="form-group ">
                                                                <input type="text" name="policy_reference"
                                                                    class="form-control policy_reference">
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-6 insurance-div" style="display: none">
                                                            <div class="form-group">
                                                                <label class="required" for="portfolio">Group Code</label>
                                                                <select class="select2 form-control insurance"
                                                                    name="group_code_id" disabled>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6 treaty-div"
                                                            style="display: none">
                                                            <div class="form-group">
                                                                <label class="required" for="portfolio">Treaty Group Code</label>
                                                                <select class="select2 form-control treaty "
                                                                    name="treaty_group_code_id" disabled>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6 fac-div"
                                                            style="display: none">
                                                            <div class="form-group">
                                                                <label class="required" for="portfolio">Facultative Group Code</label>
                                                                <select class="select2 form-control fac"
                                                                    name="fac_group_code_id" id="fac-reinsurance"
                                                                    disabled>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-12">
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
                                                    <button class="btn btn-icon btn-outline-secondary" type="button">
                                                        <span>Cancel</span>
                                                    </button>
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
            <div style="display: none;" id="entries" data-entries='@json(old('entries'))'></div>
            <div id="start" data-start="{{ $accountingYears->first()?->start_date }}"></div>
            <div id="end" data-end="{{ $accountingYears->first()?->end_date }}"></div>
        </div>
    </div>
    <!-- END: Content-->
@endSection

@section('page-css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
@endSection

@section('scripts')
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>

    <script src="{{ asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>

    <script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>

    <script src="{{ asset('assets/js/journal-validate.js') }}"></script>

    <script>
        $(document).ready(function() {

            $(document).on('keypress', '.debit', function() {
                $(this).closest('.col-md-2').next().find('.credit').val('');
            });

            $(document).on('keypress', '.credit', function() {
                $(this).closest('.col-md-2').prev().find('.debit').val('');
            });

            $(document).on('change', '#accounting-year', function() {
                fetch_year($(this).val())
            })

            var flatpickrInstance = flatpickr(".datepicker", {

                maxDate: '{{ $accountingYears->first()?->end_date }}',
                minDate: '{{ $accountingYears->first()?->start_date }}',
                altInput: true,
                altFormat: "F j, Y"
            });

            function fetch_year(year) {
                url = '{{ route('year.fetch', ['accounting_year' => ':year']) }}'.replace(':year', year)

                $.ajax({
                    'url': url,
                    'type': 'get',
                    'success': function(res) {
                        flatpickrInstance.clear()
                        flatpickrInstance.set('maxDate', res.end_date)
                        flatpickrInstance.set('minDate', res.start_date)
                        // flatpickrInstance.set('dateFormat', "M d, Y")
                        // if (filters) {
                        //     flatpickrInstance.setDate(filters['date_range']);
                        //     filters = null
                        // }
                    },
                    'error': function(err) {
                        console.log(err)
                    }

                })
            }
            repeater = $('.outer-repeater').repeater({
                isFirstItemUndeletable: false,
                defaultValues: {},
                show: function() {
                    previous = $(this).prev().find('.portfolio').html()
                    $(this).slideDown();
                    $(this).find('.portfolio').html(previous)
                    // $('#voucher')
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },
            });

            $(document).on('change', '#voucher-date', function(e) {
                $.ajax({
                    "url": '{{ route('journal.fetch.portfolio') }}',
                    "data": {
                        data: $(this).val()
                    },
                    "success": function(data) {
                        $(".portfolio").empty();
                        $.each(data, function(key, value) {
                            $(".portfolio").append("<option value=" + value.id + ">" +
                                value.name + "</option>")
                            resetSelect();

                        })
                        // hideExcept(this);

                    },
                    "error": function(err) {
                        console.log(err)
                    }
                })
            })
            $(document).on('change', '.system_department_id', function(e) {
                gl_code_id = $(this).closest('.row').find('.gl_code_id');
                var selectedOption = gl_code_id.find('option:selected');
                var dataValue = selectedOption.data('value');
                current_sys = $(this)
                if($(this).val() != ''){
                    $.ajax({
                    "url": '{{ route('journal.fetch.portfolio') }}',
                    "data": {
                        system_department_id: $(this).val(),
                        voucher_date         : $('#voucher-date').val(),
                        type                 : dataValue
                    },
                    "success": function(data) {
                       current_sys.closest(".row").find(".portfolio").empty();
                        $.each(data, function(key, value) {
                           current_sys.closest(".row").find(".portfolio").append("<option value=" + value.id + ">" +
                                value.name + "</option>")

                            resetSelect();

                        })
                        current_sys.closest(".row").find(".portfolio").select2()

                        current_sys.closest(".row").find(".portfolio").change()
                    },
                    "error": function(err) {
                        console.log(err)
                    }
                })
                }

            })

            $(document).on('change', '.portfolio', function(e) {
                portfolio = $(this).closest('.row').find('.portfolio');
                gl_code_id = $(this).closest('.row').find('.gl_code_id');
                var selectedOption = gl_code_id.find('option:selected');
                var dataValue = selectedOption.data('value');
                $.ajax({
                    "url": '{{ route('journal.fetch.group') }}',
                    "data": {
                        portfolio: portfolio.val(),
                        gl_code_id: gl_code_id.val(),
                        type: dataValue
                    },
                    "success": function(data) {
                            hideExcept(portfolio, data.type)
                            portfolio.closest('.row').find("." + data.type).empty();
                            portfolio.closest('.row').find("." + data.type).attr('disabled', false)
                            portfolio.closest('.row').find("." + data.type + "-div").show()
                            $.each(data.items, function(key, value) {
                                portfolio.closest('.row').find("." + data.type).append(
                                    "<option value=" + value.id + ">" + value.group_code +
                                    "</option>")
                            })
                            portfolio.closest('.row').find("." + data.type).select2();
                    },
                    "error": function(err) {
                        console.log(err)
                    }
                })
            })

            $(document).on('change', '.gl_code_id', function(e) {
                fetchGroupCode(this);
            })

            function fetchGroupCode(e) {
                gl_code_id = $(e).closest('.gl_code_id');
                if (gl_code_id.val() != null) {
                    var selectedOption = gl_code_id.find('option:selected');
                    var dataValue = selectedOption.data('value');
                    $.ajax({
                        "url": '{{ route('journal.fetch.departments') }}',
                        "data": {
                            gl_code_id: gl_code_id.val(),
                            type: dataValue
                        },
                        "success": function(data) {
                            $(e).closest('.row').find(".system_department_id").empty();
                            $.each(data.items, function(key, value) {
                                $(e).closest('.row').find(".system_department_id").append(
                                    "<option value=" + value.id + ">" + value.description +
                                    "</option>")
                            })
                            $(e).closest('.row').find(".system_department_id").select2();
                            $(e).closest('.row').find(".system_department_id").change();
                        },
                        "error": function(err) {
                            console.log(err)
                        }
                    })

                }
            }

            function hideExcept(e, not_div) {
                var div = ['fac', 'treaty', 'insurance', 'headoffice'];
                $.each(div, function(key, value) {
                    if (not_div != value) {
                        $(e).closest('.row').find("." + value).empty();
                        $(e).closest('.row').find("." + value).attr('disabled', true)
                        $(e).closest('.row').find("." + value + "-div").hide()
                    }
                })
            }
        });

        $("#repeater-button").click(function() {
            resetSelect()
        });

        function resetSelect() {
            setTimeout(function() {

                //$(".select2").select2({
                //

                //});
                $('.select2').removeClass('select2-hidden-accessible');
                $('.select2-container').remove();
                $('.select2').select2();

            }, 100);
        }
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
@endSection
