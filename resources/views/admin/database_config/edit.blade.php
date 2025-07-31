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
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Edit Config Information</h2>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <!-- currency edit -->
                                    <form class="form-validate" method="POST"
                                        action="{{ route('db_config.update', [CustomHelper::encode($config->id)]) }}"
                                        autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="required" for="name">Name</label>
                                                    <input type="text" class="form-control" placeholder="Currency Name"
                                                        value="{{ Request::old('name') ?? $config->name }}" name="name"
                                                        id="name" />
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
