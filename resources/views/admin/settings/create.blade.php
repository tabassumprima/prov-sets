@extends('admin.layouts.app')
@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />

                    <section class="currency-store">
                        <div class="card mb-3">
                            <div class="card-header">
                                <ul class="nav nav-pills card-header-pills" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link active" role="tab" data-toggle="tab"
                                            data-target="#navs-pills-within-card-active"
                                            aria-controls="navs-pills-within-card-active" aria-selected="true">General</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link" role="tab" data-toggle="tab"
                                            data-target="#insurance-tab" aria-controls="navs-pills-within-card-link"
                                            aria-selected="false" tabindex="-1">Insurance</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link" role="tab" data-toggle="tab"
                                            data-target="#reinsurance-tab" aria-controls="navs-pills-within-card-link"
                                            aria-selected="false" tabindex="-1">Reinsurance</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link" role="tab" data-toggle="tab"
                                            data-target="#provision-tab" aria-controls="navs-pills-within-card-link"
                                            aria-selected="false" tabindex="-1">Provision</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link" role="tab" data-toggle="tab"
                                            data-target="#import-tab" aria-controls="navs-pills-within-card-link"
                                            aria-selected="false" tabindex="-1">Import</button>
                                    </li>

                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                <div class="tab-pane fade show active" id="navs-pills-within-card-active"
                                    role="tabpanel">
                                    <form class="form-validate" method="POST" action="{{ route('settings.store') }}" autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                            <input type="hidden" name="form_type" value="general">
                                                <div class="form-group">
                                                    <label class="required" for="unallocated_portfolio_id">Unallocated Portfolio</label>
                                                    <select class="select2 form-control glcode" name="options[unallocated_portfolio_id]">
                                                        <option disabled selected>Select Unallocation Portfolio</option>
                                                        @foreach ($portfolios as $portfolio)
                                                            <option value={{ $portfolio->id }} {{ $settings->get('unallocated_portfolio_id') == $portfolio->id || old('options.unallocated_portfolio_id') == $portfolio->id ? 'selected' : null }}>
                                                                {{ $portfolio->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="name">Headoffice Portfolio</label>
                                                    <select class="select2 form-control glcode" name="options[headoffice_portfolio_id]">
                                                        <option disabled selected>Select Headoffice Portfolio</option>
                                                        @foreach ($portfolios as $portfolio)
                                                            <option value={{ $portfolio->id }} {{ $settings->get('headoffice_portfolio_id') == $portfolio->id || old('options.headoffice_portfolio_id') == $portfolio->id ? 'selected' : null }}>
                                                                {{ $portfolio->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="name">Lambda Posting Voucher Type</label>
                                                    <select class="select2 form-control glcode" name="options[lambda_posting_voucher_id]">
                                                        <option disabled selected>Select Lambda Posting Voucher Type</option>
                                                        @foreach ($voucherTypes as $voucherType)
                                                            <option value={{ $voucherType->id }} {{ $settings->get('lambda_posting_voucher_id') == $voucherType->id || old('options.lambda_posting_voucher_id') == $voucherType->id ? 'selected' : null }}>
                                                                {{ $voucherType->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="name">Management Expense Level</label>
                                                    <select class="select2 form-control glcode" name="options[management_expense_level_id]">
                                                        <option disabled selected>Select Management Expense</option>
                                                        @foreach ($levels as $level)
                                                            <option value={{ $level->id }} {{ $settings->get('management_expense_level_id') == $level->id || old('options.management_expense_level_id') == $level->id ? 'selected' : null }}>
                                                                {{ $level->id }} - {{ $level->level }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="name">Marine Exposure Days</label>
                                                    <input type="number" class="form-control" placeholder="Marine Exposure Days"
                                                        value="{{ Request::old('options.marine_exposure_days') ?? $settings->get('marine_exposure_days') }}"
                                                        name="options[marine_exposure_days]" id="name" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="name">IBNR Period Year</label>
                                                    <input type="text" class="form-control" placeholder="IBNR Period Year"
                                                        value="{{ Request::old('options.ibnr_period_year') ?? $settings->get('ibnr_period_year') }}"
                                                        name="options[ibnr_period_year]" id="name" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="name">Discount Period Year</label>
                                                    <input type="text" class="form-control" placeholder="Discount Period Year"
                                                        value="{{ Request::old('options.discounting_period_year') ?? $settings->get('discounting_period_year') }}"
                                                        name="options[discounting_period_year]" id="name" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-sm-row flex-column mt-2" id="setting-button">
                                                    <x-form-buttons textSubmit='Save Setting' />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade show" id="insurance-tab" role="tabpanel">
                                    <form class="form-validate" method="POST" action="{{ route('settings.store') }}" autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="hidden" name="form_type" value="marine-insurance">
                                                <div class="form-group">
                                                    <label class="required" for="name">Marine Insurance Products </label>
                                                    <select class="selectpicker form-control glcode " multiple="multiple" name="options[marine_products_id][]"
                                                    data-selected-text-format="count > 1"
                                                    data-count-selected-text="{0} products selected" data-live-search="true" data-size="5"
                                                    data-none-selected-text="Select">
                                                        <option disabled>Select Marine Insurance</option>
                                                        @foreach ($products as $product)
                                                            <option value={{ $product->id }} {{  collect($settings->get('marine_products_id'))->contains($product->id) || collect(old('options.marine_products_id'))->contains($product->id)? 'selected' : null }}>
                                                                {{ $product->code }} - {{ $product->description}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-sm-row flex-column mt-2" id="setting-button">
                                                    <x-form-buttons textSubmit='Save Setting' />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade show" id="reinsurance-tab" role="tabpanel">
                                    <form class="form-validate" method="POST" action="{{ route('settings.store') }}" autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                            <input type="hidden" name="form_type" value="marine-reinsurance">
                                            <div class="form-group">
                                                <label class="required" for="name">Marine Reinsurance Products </label>
                                                <select class="selectpicker form-control glcode " multiple="multiple" name="options[marine_reproducts_id][]"
                                                data-selected-text-format="count > 1"
                                                data-count-selected-text="{0} products selected" data-live-search="true" data-size="5"
                                                data-none-selected-text="Select">
                                                    <option disabled>Select Marine Reinsurance</option>
                                                    @foreach ($reproducts as $reproduct)
                                                        <option value={{ $reproduct->id }} {{  collect($settings->get('marine_reproducts_id'))->contains($reproduct->id) || collect(old('options.marine_reproducts_id'))->contains($reproduct->id)? 'selected' : null }}>
                                                            {{ $reproduct->treaty_pool }} - {{ $reproduct->description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-sm-row flex-column mt-2" id="setting-button">
                                                    <x-form-buttons textSubmit='Save Setting' />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <div class="tab-pane fade show" id="provision-tab" role="tabpanel">
                                    <form class="form-validate" method="POST" action="{{ route('settings.store') }}" autocomplete="off">
                                        @csrf
                                        <input type="hidden" name="form_type" value="provision">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="unallocated_portfolio_id">Post Entry Lambda</label>
                                                    <select class="select2 form-control glcode" name="options[post_entry_lambda_id]">
                                                        <option disabled selected>Select Post Entry Lambda</option>
                                                        @foreach ($lambdas as $lambda)
                                                            <option value={{ $lambda->id }} {{ $settings->get('post_entry_lambda_id') == $lambda->id || old('options.post_entry_lambda_id') == $lambda->id ? 'selected' : null }}>
                                                                {{ $lambda->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="unallocated_portfolio_id">Fail Lambda</label>
                                                    <select class="select2 form-control glcode" name="options[fail_lambda_id]">
                                                        <option disabled selected>Select Fail Lambda</option>
                                                        @foreach ($lambdas as $lambda)
                                                            <option value={{ $lambda->id }} {{ $settings->get('fail_lambda_id') == $lambda->id || old('options.fail_lambda_id') == $lambda->id ? 'selected' : null }}>
                                                                {{ $lambda->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="name">Opening Balance Lambda </label>
                                                    <select class="select2 form-control glcode" name="options[opening_balance_lambda_id]">
                                                        <option disabled selected>Select Opening Balance Lambda</option>
                                                        @foreach ($lambdas as $lambda)
                                                            <option value={{ $lambda->id }} {{ $settings->get('opening_balance_lambda_id') == $lambda->id || old('options.opening_balance_lambda_id') == $lambda->id ? 'selected' : null }}>
                                                                {{ $lambda->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-sm-row flex-column mt-2" id="setting-button">
                                                    <x-form-buttons textSubmit='Save Setting' />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade show" id="import-tab" role="tabpanel">
                                    <form class="form-validate" method="POST" action="{{ route('settings.store') }}" autocomplete="off">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                            <input type="hidden" name="form_type" value="import">
                                                <div class="form-group">
                                                    <label for="val-date">Transition Date</label>
                                                    <input type="text" id="transition_date" name="options[transition_date]" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"
                                                    value="{{ Request::old('options.transition_date') ?? $settings->get('transition_date') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required" for="auto-import">Auto Import</label>
                                                    <select id="is_auto_import" name="options[is_auto_import]" class="select2 form-control">
                                                        <option value="true" {{ $settings->get('is_auto_import') == 'true' ? 'selected="selected"' : '' }}>
                                                            Enabled</option>
                                                        <option value="false" {{ $settings->get('is_auto_import') == 'false' ? 'selected="selected"' : '' }}>
                                                            Disabled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-sm-row flex-column mt-2" id="setting-button">
                                                    <x-form-buttons textSubmit='Save Setting' />
                                                </div>
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
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
@endsection
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css') }}">
<style>
    .btn-light {
    color: #2A2E30;
    background-color: transparent;
    border-color: #F6F6F6;
}
</style>
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets/vendors/css/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/css/dashboard.css') }}">

@endSection
@section('scripts')
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/ui/popper.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script>
    $(document).ready(function() {
            //Load road tenancy param
            updateUrl(`{{ Request::input('org') }}`);
     });
</script>
@endsection
