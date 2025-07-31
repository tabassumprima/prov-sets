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
                <!-- users edit start -->
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Edit Organization Information</h2>
                                <div style="margin-left: auto;">
                                    <a href="{{ route('subscriptions.index') }}"
                                        class="btn btn-primary btn-lg waves-effect waves-float waves-light">Manage
                                        Subscription</a>
                                </div>
                            </ul>
                            <div class="tab-content">
                                <!-- Account Tab starts -->
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <!-- users edit account form start -->
                                    <form class="form-validate" method="POST" enctype="multipart/form-data"
                                        action="{{ route('organizations.update', [CustomHelper::encode($organization->id)]) }}"
                                        autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <input type="hidden"
                                                        value="{{ CustomHelper::encode($organization->id) }}"
                                                        name="id" />
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="required" for="organization_name ">Organization
                                                                Name</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="X organization"
                                                                value="{{ $organization->name }}" name="name"
                                                                id="organization_name" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label required"
                                                                for="user-role">Country</label>
                                                            <select id="country_id" name="country_id"
                                                                class="select2 form-control" required>
                                                                @foreach ($countries as $country)
                                                                    <option value="{{ $country->id }}"
                                                                        {{ $organization->country->name == $country->name ? 'selected="selected"' : '' }}>
                                                                        {{ $country->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label required"
                                                                for="user-role">Currency</label>
                                                            <select id="currency_id" name="currency_id"
                                                                class="select2 form-control" required>
                                                                @foreach ($currencies as $currency)
                                                                    <option value="{{ $currency->id }}"
                                                                        {{ $organization->currency->name == $currency->name ? 'selected="selected"' : '' }}>
                                                                        {{ $currency->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label required" for="user-role">Type</label>
                                                            <select id="type" name="type" class="select2 form-control" required>
                                                                <option value="0" {{ $organization->type == '0' ? 'selected="selected"' : '' }}>
                                                                    Life Insurance
                                                                </option>
                                                                <option value="1" {{ $organization->type == '1' ? 'selected="selected"' : '' }}>
                                                                    Non-Life Insurance
                                                                </option>
                                                                <option value="2" {{ $organization->type == '2' ? 'selected="selected"' : '' }}>
                                                                    Composite
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label"
                                                                for="basic-icon-default-fullname">Subscription
                                                                Plan</label>
                                                            <select id="type" name="subscription_plan"
                                                                class="select2 form-control" readonly disabled>
                                                                @if ($organization->activePlan)
                                                                    @foreach ($plans as $plan)
                                                                        <option value="{{ $plan->id }}"
                                                                            {{ $plan->id == $organization->activePlan?->plan_id ? 'selected' : null }}>
                                                                            {{ $plan->name .
                                                                                ' ( ' .
                                                                                $plan->duration_in_text .
                                                                                ' )' .
                                                                                ' ( Ends At: ' .
                                                                                Carbon\Carbon::parse($organization->activePlan?->ends_at)->format(config('constant.datetime_format')) .
                                                                                ' )' }}
                                                                        </option>
                                                                    @endforeach
                                                                @else
                                                                    <option>No active plan</option>
                                                                @endif

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label required"
                                                                for="basic-icon-default-email">Address</label>
                                                            <input type="text" id="basic-icon-default-email"
                                                                class="form-control dt-email"
                                                                value="{{ $organization->address }}"
                                                                placeholder="123 Street"
                                                                aria-describedby="basic-icon-default-email2"
                                                                name="address" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label required"
                                                                for="basic-icon-default-email">Organization FBR NTN
                                                                Number</label>
                                                            <input type="text" id="basic-icon-default-email"
                                                                class="form-control dt-email"
                                                                value="{{ $organization->ntn_number }}" placeholder="12345"
                                                                aria-describedby="basic-icon-default-email2"
                                                                name="ntn_number" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label required" for="user-role">Organization
                                                                Shortcode</label>
                                                            <input type="text" id="basic-icon-default-email"
                                                                class="form-control dt-email" placeholder="JBL"
                                                                aria-describedby="basic-icon-default-email2"
                                                                name="shortcode"
                                                                value="{{ $organization->shortcode }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="organization_logo">Upload Logo </label>
                                                            @if ($organization->logo)
                                                                <a
                                                                    href="{{ route('organization.logo', ['organization' => CustomHelper::encode($organization->id)]) }}"><span
                                                                        data-feather="eye"></span></a>
                                                            @endif
                                                            <div class="custom-file">
                                                                <input type="file"name="logo"
                                                                    class="custom-file-input" id="organization_logo">
                                                                <label class="custom-file-label"
                                                                    for="organization_logo">Choose file</label>
                                                            </div>
                                                            <small id="file-error" class="text-danger d-none">File size must be less than 1MB.</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label required"
                                                                for="basic-icon-default-email">Organization Sales Tax
                                                                Number</label>
                                                            <input type="text" id="basic-icon-default-email"
                                                                class="form-control dt-email"
                                                                value="{{ $organization->sales_tax_number }}"
                                                                placeholder="12345"
                                                                aria-describedby="basic-icon-default-email2"
                                                                name="sales_tax_number" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label required"
                                                                for="basic-icon-default-fullname">Financial Year</label>
                                                            <select id="financial_year" name="financial_year"
                                                                class="select2 form-control">
                                                                @foreach ($financial_years as $key => $financial_year)
                                                                    <option value="{{ $key }}"
                                                                        {{ $organization->financial_year == $key ? 'selected' : '' }}>
                                                                        {{ $financial_year }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label required" for="user-role">Database
                                                                Config
                                                                ID</label>
                                                            <select id="database_config_id" name="database_config_id"
                                                                class="select2 form-control" required>
                                                                @foreach ($configs as $config)
                                                                    <option value="{{ $config->id }}"
                                                                        {{ optional($organization->database_config)->name == $config->name ? 'selected="selected"' : '' }}>
                                                                        {{ $config->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="basic-icon-default-email">Agent
                                                                Configuration</label>
                                                            <input type="text" id="basic-icon-default-email"
                                                                class="form-control dt-email"
                                                                value="{{ $organization->agent_config }}" placeholder=""
                                                                aria-describedby="basic-icon-default-email2"
                                                                name="agent_config" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <x-form-buttons textSubmit='Save Changes' />
                                            </div>
                                        </div>
                                    </form>
                                    <!-- users edit account form ends -->
                                </div>
                                <!-- Account Tab ends -->
                            </div>
                        </div>
                </section>
                <!-- users edit ends -->
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

    <script type="text/javascript">
        $(document).ready(function() {
            updateUrl(`{{ Request::input('org') }}`);
        });
    </script>
@endSection
