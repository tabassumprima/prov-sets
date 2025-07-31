@extends('auth.layouts.app', ['title' => 'Login'])
@section('content')
    <!-- Left Text-->
    <div class="d-none d-lg-flex col-lg-8 align-items-center p-5 auth-bg">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center align-items-center justify-content-center">
                        <img class="img-fluid" src="{{ asset('app-assets/images/logo/delta-logo.svg') }}" alt="Delta" /> 
                    </div>
                    <div class="col-12 text-center">
                        <h1>An IFRS 17 Solution for Insurance Companies</h1>
                    </div>
                </div>
            </div>
        </div>

        
        
    </div>
    <!-- /Left Text-->
    <!-- Login-->
    <div class="d-flex col-lg-4 align-items-center px-2 p-lg-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
            <h4 class="card-title mb-1">Welcome to Delta! 👋</h4>
            <p class="card-text mb-2">Please sign-in to your account</p>

            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-body">
                            Error: {{ $error }}
                        </div>
                    </div>
                @endforeach
            @endif

            @if(session('status'))
                <div class="alert alert-success" role="alert">
                    <div class="alert-body">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <form class="auth-login-form mt-2" method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="login-email">Email</label>
                    <input class="form-control" id="login-email" type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" aria-describedby="login-email" autofocus="" tabindex="1" required />
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <label for="login-password">Password</label><a href="{{ route('password.request') }}"><small>Forgot Password?</small></a>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input class="form-control form-control-merge" id="password" type="password" name="password" placeholder="············" aria-describedby="login-password" tabindex="2" required />
                        <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" id="remember-me" type="checkbox" tabindex="3" />
                        <label class="custom-control-label" for="remember-me"> Remember Me</label>
                    </div>
                </div>
                <button class="btn btn-primary btn-block" tabindex="4" id="loginFormButton"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" style="display: none;"></span>
                    <span class="loading-text">Sign in</span></button>
            </form>
        </div>
    </div>
    <!-- /Login-->
@endSection
@section('scripts')
<script type="text/javascript">
        $('#loginForm').submit(function(event) {
            // Get the values of email and password fields
            var emailValue = $('#login-email').val();
            var passwordValue = $('#password').val();

            // Check if both email and password are not empty
            if (emailValue.trim() !== '' && passwordValue.trim() !== '') {
                // Disable the button and show loading indicators
                $('#loginFormButton').prop('disabled', true);
                $('#loginFormButton').find('.spinner-grow').show();
                $('#loginFormButton').find('.loading-text').text('Logging in');

            }
        });
</script>
@endSection
