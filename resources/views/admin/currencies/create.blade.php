@extends('admin.layouts.app')
@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <x-toast :errors="$errors"/>
                <!-- valuation input start -->
                <form class="form-validate" method="POST" action="{{ route('currencies.store') }}" autocomplete="off">
                    <section class="currency-store">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills" role="tablist">
                                    <h2>Currency Add</h2>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="currency">Currency</label>
                                                    <input type="text" class="form-control" placeholder="Currency Name"
                                                        value="{{ Request::old('name') }}" name="name" id="currency_name" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="symbol">Symbol</label>
                                                    <input type="text" class="form-control" placeholder="Currency Symbol"
                                                        value="{{ Request::old('symbol') }}" name="symbol"
                                                        id="currency_symbol" />
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
                            <x-form-buttons textSubmit='Add Currency'/>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endSection
