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
                <form class="add-new-user pt-0" method="POST" enctype="multipart/form-data"
                    action="{{ route('organizations.store') }}">
                    <section class="app-user-list">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills" role="tablist">
                                    <h2>Add New Organization Information</h2>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="account" aria-labelledby="account-tab"
                                        role="tabpanel">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required"
                                                        for="basic-icon-default-fullname">Organization
                                                        Name</label>
                                                    <input type="text" class="form-control dt-full-name"
                                                        id="basic-icon-default-fullname" placeholder="X Organization"
                                                        name="name" aria-label="John Doe"
                                                        aria-describedby="basic-icon-default-fullname2"
                                                        value="{{ old('name') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required"
                                                        for="basic-icon-default-fullname">Country</label>
                                                    <select id="country_id" name="country_id" class="select2 form-control">
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}"
                                                                {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                                {{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required"
                                                        for="basic-icon-default-fullname">Currency</label>
                                                    <select id="currency_id" name="currency_id" class="select2 form-control"
                                                        value="{{ old('currency_id') }}">
                                                        @foreach ($currencies as $currency)
                                                            <option value="{{ $currency->id }}"
                                                                {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                                                {{ $currency->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required"
                                                        for="basic-icon-default-fullname">Type</label>
                                                        <select id="type" name="type" class="select2 form-control" value="{{ old('type') }}">
                                                            @foreach ($insurance_types as $index => $insurance_type)
                                                                <option value="{{ $index }}" {{ old('type') == $index ? 'selected' : '' }}>
                                                                    {{ $insurance_type }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="hidden" name="subscription_plan">
                                                    <label class="form-label required"
                                                        for="basic-icon-default-fullname">Subscription
                                                        Plan</label>
                                                    <select id="type" name="subscription_plan"
                                                        class="select2 form-control">
                                                        <option selected disabled> Select any option </option>
                                                        @foreach ($plans as $plan)
                                                            <option value="{{ $plan->id }}">
                                                                {{ $plan->name . ' ( ' . $plan->duration_in_text . ' )' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required" for="user-role">Organization
                                                        Address</label>
                                                    <input type="text" id="basic-icon-default-email"
                                                        class="form-control dt-email" placeholder="123 Street."
                                                        aria-describedby="basic-icon-default-email2" name="address"
                                                        value="{{ old('address') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required" for="user-role">Organization
                                                        Shortcode</label>
                                                    <input type="text" id="basic-icon-default-email"
                                                        class="form-control dt-email" placeholder="JBL"
                                                        aria-describedby="basic-icon-default-email2" name="shortcode"
                                                        value="{{ old('shortcode') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="organization_logo">Upload Logo</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="logo" class="custom-file-input"
                                                            accept="image/png, image/jpeg" id="organization_logo"
                                                            value="{{ old('logo') }} ">
                                                        <label class="custom-file-label" for="organization_logo">Choose
                                                            file</label>
                                                    </div>
                                                    <small id="file-error" class="text-danger d-none">File size must be less than 1MB.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required" for="user-role">Organization FBR
                                                        NTN
                                                        Number</label>
                                                    <input type="text" id="basic-icon-default-email"
                                                        class="form-control dt-email" placeholder="123456"
                                                        aria-label="john.doe@example.com"
                                                        aria-describedby="basic-icon-default-email2" name="ntn_number"
                                                        value="{{ old('ntn_number') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required"
                                                        for="basic-icon-default-fullname">Financial
                                                        Year</label>
                                                    <select id="financial_year" name="financial_year"
                                                        class="select2 form-control">
                                                        @foreach ($financial_years as $key => $financial_year)
                                                            <option value="{{ $key }}"
                                                                {{ old('financial_year') == $key ? 'selected' : '' }}>
                                                                {{ $financial_year }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group ">
                                                    <label class="form-label required" for="user-role">Sales Tax
                                                        Number</label>
                                                    <input type="text" id="basic-icon-default-email"
                                                        class="form-control dt-email" placeholder="123456"
                                                        aria-label="john.doe@example.com"
                                                        aria-describedby="basic-icon-default-email2"
                                                        name="sales_tax_number" value="{{ old('sales_tax_number') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required"
                                                        for="basic-icon-default-fullname">Database
                                                        Config
                                                        ID</label>
                                                    <select id="database_config_id" name="database_config_id"
                                                        class="select2 form-control">
                                                        @foreach ($configs as $config)
                                                            <option
                                                                value="{{ $config->id }}"{{ old('database_config_id') == $config->id ? 'selected' : '' }}>
                                                                {{ $config->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="user-role">Agent Configuration</label>
                                                    <input type="text" id="basic-icon-default-email"
                                                        class="form-control dt-email" placeholder=""
                                                        aria-label="john.doe@example.com"
                                                        aria-describedby="basic-icon-default-email2"
                                                        name="agent_config" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                    <x-form-buttons textSubmit='Submit' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        </div>
    </div>
@endSection

@section('scripts')
<script>
    document.getElementById('organization_logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const maxSize = 1 * 1024 * 1024;

        if (file && file.size > maxSize) {
            document.getElementById('file-error').classList.remove('d-none');
            event.target.value = '';
        } else {
            document.getElementById('file-error').classList.add('d-none');
        }
    });
</script>
@endSection
