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
                <!-- valuation input start -->
                <form class="form-validate" method="POST" action="{{ route('portfolio.store') }}" autocomplete="off">
                    <section class="country-store">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills" role="tablist">
                                    <h2>Portfolio Add</h2>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="name">Name</label>
                                                    <input type="text" class="form-control" placeholder="Portfolio Name"
                                                        value="{{ Request::old('name') }}" name="name"
                                                        id="name" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="shortcode">ShortCode</label>
                                                    <input type="text" class="form-control" placeholder="Shortcode"
                                                        value="{{ Request::old('shortcode') }}" name="shortcode"
                                                        id="shortcode" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="row">
                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                            <x-form-buttons textSubmit='Add Portfolio' />
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endSection
