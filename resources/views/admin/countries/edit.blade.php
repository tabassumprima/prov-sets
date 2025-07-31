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

                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Edit Country Information</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <!-- country edit -->
                                    <form class="form-validate" method="POST"
                                        action="{{ route('countries.update', [CustomHelper::encode($country->id)]) }}"
                                        autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="country">Country</label>
                                                    <input type="text" class="form-control" placeholder="Country Name"
                                                        value="{{ Request::old('name') ?? $country->name }}" name="name"
                                                        id="country_name" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="code">Code</label>
                                                    <input type="text" class="form-control" placeholder="Country Symbol"
                                                        value="{{ Request::old('code') ?? $country->code }}" name="code"
                                                        id="country_code" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="timeZone">Time Zone</label>
                                                    <select class="select2 form-control form-control-lg" name="timeZone">
                                                        @foreach (timezone_identifiers_list() as $zone)
                                                            @php
                                                                $timestamp = time();
                                                                date_default_timezone_set($zone);
                                                                $zones['offset'] = date('P', $timestamp);
                                                                // $zones['diff_from_gtm'] = 'UTC/GMT ' . date('P', $timestamp);
                                                            @endphp
                                                            <option value=" {{ $zone . '|' . $zones['offset'] }}"
                                                                {{ $zone . '|' . $zones['offset'] == $country->zone . '|' . $country->offset ? 'selected' : '' }}>
                                                                {{ $zone . '  ' . $zones['offset'] }}
                                                            </option>
                                                        @endforeach
                                                        @php
                                                            //back to default time
                                                            date_default_timezone_set(config('app.timezone'));
                                                        @endphp
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <x-form-buttons textSubmit='Save Changes' />
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
