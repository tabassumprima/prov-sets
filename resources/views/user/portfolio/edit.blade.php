@extends('user.layouts.app')
@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper w-100">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                    </li>
                                    @if ($portfolio->type == 'insurance')
                                        <li class="breadcrumb-item">Insurance</li>
                                    @else
                                        <li class="breadcrumb-item">Reinsurance</li>
                                    @endif
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <x-toast :errors="$errors" />

                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Edit portfolio Information</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <!-- portfolio edit -->
                                    <form class="form-validate" method="POST"
                                        action="{{ route('portfolios.update', [CustomHelper::encode($portfolio->id)]) }}"
                                        autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{CustomHelper::encode($portfolio->id)}}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="portfolio">portfolio</label>
                                                    <input type="text" class="form-control" placeholder="portfolio Name"
                                                        value="{{ Request::old('name') ?? $portfolio->name }}" name="name"
                                                        id="portfolio_name" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="code">Code</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ Request::old('shortcode') ?? $portfolio->shortcode }}" disabled
                                                        id="country_code" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label required" for="group-desc">Type</label>
                                                    <select class="form-control" id="type" name="type">
                                                        @foreach (config('constant.applicable_to') as $key => $type)
                                                            <option value="{{ $key }}" {{ (Request::old('type') == $portfolio->type || $key == $portfolio->type) ? 'selected' : '' }}>{{ $type }}</option>
                                                        @endforeach
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
