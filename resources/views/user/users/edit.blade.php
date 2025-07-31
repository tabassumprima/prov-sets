@extends('user.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <x-toast :errors="$errors"/>
                <div class="alert" style="display: none;" role="alert">
                    <div class="alert-body">

                    </div>
                </div>
                <!-- users edit start -->
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Change Password</h2>
                            </ul>
                            <div class="tab-content">
                                <!-- Account Tab starts -->
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                                    <!-- users edit account form start -->
                                    <form class="form-validate" method="POST"
                                        action="{{ route('user.update', [$id]) }}"
                                        autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="password">Current Password</label>
                                                            <div
                                                                class="input-group input-group-merge form-password-toggle ">
                                                                <input type="password" class="form-control"
                                                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                                    aria-describedby="login-password" name="current_password"
                                                                    id="current_password" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text cursor-pointer"><i
                                                                            data-feather="eye"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="password">New Password</label>
                                                            <div
                                                                class="input-group input-group-merge form-password-toggle ">
                                                                <input type="password" class="form-control"
                                                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                                    aria-describedby="login-password" name="password"
                                                                    id="password" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text cursor-pointer"><i
                                                                            data-feather="eye"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="confirm-password">Confirm Password</label>
                                                            <div
                                                                class="input-group input-group-merge form-password-toggle ">
                                                                <input type="password" class="form-control"
                                                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                                    aria-describedby="login-password"
                                                                    name="password_confirmation" id="confirm-password" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text cursor-pointer"><i
                                                                            data-feather="eye"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex flex-sm-row flex-column mt-1">
                                                        <div class="form-group">
                                                            <output name="result" id="result" style="color:red"></output>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                               <x-form-buttons textSubmit="Save Changes" />
                                            </div>
                                        </div>
                                    </form>
                                    <!-- users edit account form ends -->
                                </div>
                                <!-- Account Tab ends -->
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endSection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[type="password"]').keyup(function() {
                var password = $('#password').val();
                var confirm_password = $('#confirm-password').val();
                if (password == confirm_password) {
                    $('#result').html('');
                    $('#submit').prop('disabled', false);
                } else {
                    $('#result').html('Passwords do not match!');
                    $('#submit').prop('disabled', true);
                }
            });
        });
    </script>
@endSection
