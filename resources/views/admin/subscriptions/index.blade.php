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
                <section class="app-user-list">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Manage Subscription</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <form method="post" action="{{ route('subscriptions.store') }}">
                                        @csrf
                                        @if ($organization->activePlan)
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">
                                                        Active Plan
                                                    </label>
                                                    <h6>
                                                        {{ $organization->activePlan->plan->name . ' ( ' . $organization->activePlan->plan->duration_in_text . ' )' }}
                                                    </h6>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">
                                                        Starts At
                                                    </label>
                                                    <h6>
                                                        {{ Carbon\Carbon::parse($organization->activePlan->starts_at)->format(config('constant.datetime_format')) }}
                                                    </h6>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">
                                                        Ends At
                                                    </label>
                                                    <h6>
                                                        {{ Carbon\Carbon::parse($organization->activePlan->ends_at)->format(config('constant.datetime_format')) }}
                                                    </h6>
                                                </div>
                                            </div>
                                        @else
                                            <h5>No plan subscribe please select any</h5>
                                        @endif
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="hidden" name="subscription_id"
                                                        value="{{ $organization->activePlan?->id }}">
                                                    <input type="hidden" name="active_plan"
                                                        value="{{ $organization->activePlan?->plan_id }}">
                                                    <input type="hidden" name="organization_id"
                                                        value="{{ $organization->id }}">
                                                    <label class="form-label required"
                                                        for="basic-icon-default-fullname">Subscription
                                                        Plan</label>
                                                    <select id="type" name="subscription_plan"
                                                        class="select2 form-control">
                                                        <option  selected disabled> -- Select any option --</option>
                                                        @foreach ($plans as $plan)
                                                            <option value="{{ $plan->id }}"
                                                                {{ $plan->id == $organization->activePlan?->plan_id ? 'selected' : null }}>
                                                                {{ $plan->name . ' ( ' . $plan->duration_in_text . ' )' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @if ($organization->activePlan)
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Add
                                                            extra days</label>
                                                        <input type="number" name="add_extra_days" class="form-control"
                                                            placeholder="2" maxlength="3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                                <x-form-buttons textSubmit='Submit' />
                                            </div>
                                        </div>
                                    </form>
                                    @if ($organization->activePlan)
                                        <div class="row">
                                            <form
                                                action="{{ route('subscriptions.destroy', $organization->activePlan->id) }}"
                                                method="POST" onsubmit="return confirmDelete();">
                                                @csrf
                                                @method('delete')
                                                <div style="margin-left: auto;" class="right">
                                                    <button type="submit"
                                                        class="btn btn-danger mb-0 mt-3 mb-sm-0 mr-0 mr-sm-1 ml-sm-1">Cancel
                                                        Subscription</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
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
    <script type="text/javascript">
        function confirmDelete() {
            return confirm('Are you sure you want to cancel subscription?');
        }

        $(document).ready(function() {
            updateUrl(`{{ Request::input('org') }}`);
        });
    </script>
@endSection
