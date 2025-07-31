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
                <div class="custom-alert alert" style="display: none;" role="alert">
                    <div class="alert-body">

                    </div>
                </div>
                <!-- users edit start -->
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>Edit Account Information</h2>
                                <div style="margin-left: auto;">
                                    <button type="button" id="welcome-mail" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1 ml-sm-1">Welcome Mail</button>
                                    <button type="button" id="reset-mail" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1 ml-sm-1">Reset Mail</button>
                                </div>
                            </ul>
                            <div class="tab-content">
                                <!-- Account Tab starts -->
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                                    <!-- users edit account form start -->
                                    <form class="form-validate" method="POST"
                                        action="{{ route('users.update', [CustomHelper::encode($user->id)]) }}"
                                        autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <input type="hidden" value="{{ CustomHelper::encode($user->id) }}"
                                                        name="user_id" />
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="username" class="required">Full name</label>
                                                            <input type="text" class="form-control" placeholder="Username"
                                                                value="{{ $user->name }}" name="name" id="name" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="name" class="required">E-mail</label>
                                                            <input type="email" class="form-control" placeholder="email"
                                                                value="{{ $user->email }}" name="email" id="email" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="phone" class="required">Phone Number</label>
                                                            <input minlength="7" type="text" class="form-control" placeholder="phone"
                                                                value="{{ $user->phone }}" name="phone" id="phone" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select class="form-control" id="is_active" name="is_active">
                                                                <option value="1"
                                                                    {{ $user->is_active == 1 ? 'selected="selected"' : '' }}>
                                                                    Enabled</option>
                                                                <option value="0"
                                                                    {{ $user->is_active == 0 ? 'selected="selected"' : '' }}>
                                                                    Disabled</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="user-role">User Role</label>
                                                            <select id="user_role" name="user_role" class="form-control">
                                                                @foreach ($roles as $key => $role)
                                                                    <option value="{{ $key }}"
                                                                        {{ $role == $user_role ? 'selected="selected"' : '' }}>
                                                                        {{ $role }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label required" for="user-role">User Verification Type</label>
                                                            <select id="verification_type" name="verification_type" class="select2 form-control">
                                                                <option value="email"
                                                                    {{ $user->verification_type == 'email' ? 'selected="selected"' : '' }}>
                                                                    Email</option>
                                                                <option value="2fa"
                                                                    {{ $user->verification_type == '2fa' ? 'selected="selected"' : '' }}>
                                                                    2fa</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="password">Password</label>
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
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <h2>2FA Managment</h2>
                            </ul>
                            <div class="tab-content">
                                <!-- Account Tab starts -->
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input type="hidden" value="{{ CustomHelper::encode($user->id) }}"
                                                    name="user_id" />
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="google2fa_secret">Secret Key</label>
                                                        <input type="text" class="form-control" placeholder="Username"
                                                            value="{{ $user->google2fa_secret }}" name="google2fa_secret"
                                                            id="name" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                            <button type="button" id='genKey'
                                                class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Generate New Key</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Account Tab ends -->
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users edit ends -->

            </div>
        </div>
    </div>
@endSection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            updateUrl(`{{ Request::input('org') }}`);     //Load road tenancy param
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
            $("#genKey").on('click', function() {
                $.ajax({
                    url: "{{ route('users.generate2fa', [CustomHelper::encode($user->id)]) }}",
                    type: "POST",
                    data: {
                        'user_id': $('input[name="user_id"]').val(),
                        '_token': $('input[name="_token"]').val()
                    },
                    success: function(data) {
                        location.reload();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(errorThrown + textStatus, +xhr);
                        return false;
                    }
                });
            });
            $("#welcome-mail").click(function(){
            this.disabled = true;
            $.ajax({
                url: '{{route("users.welcome-mail", CustomHelper::encode($user->id))}}',
                type: 'POST',
                data: {
                    '_token': '{{csrf_token()}}',
                },
                success: function(data) {
                    $(".custom-alert").css({'display':'block'}).addClass('alert-success');
                    $(".custom-alert > .alert-body").html(data.message);
                },
                error: function(data, status, error) {
                    $(".custom-alert").css({'display':'block'}).addClass('alert-danger');
                    $(".custom-alert > .alert-body").html('Error: '+error);
                    $("#welcome-mail").attr('disabled', false);
                }
            });
        });
        $("#reset-mail").click(function(){
            this.disabled = true;
            $.ajax({
                url: '{{route("users.reset-mail", CustomHelper::encode($user->id))}}',
                type: 'POST',
                data: {
                    '_token': '{{csrf_token()}}',
                },
                success: function(data) {
                    $(".custom-alert").css({'display':'block'}).addClass('alert-success');
                    $(".custom-alert > .alert-body").html(data.message);
                },
                error: function(data, status, error) {
                    $(".custom-alert").css({'display':'block'}).addClass('alert-danger');
                    $(".custom-alert > .alert-body").html('Error: '+error);
                    $("#reset-mail").attr('disabled', false);
                }
            });
        });
        });
    </script>
@endSection
