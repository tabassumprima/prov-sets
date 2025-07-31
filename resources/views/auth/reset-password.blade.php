@extends('auth.layouts.app', ['title' => 'Reset password'])
@section('content')
     <!-- Left Text-->
    <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img src="{{ asset('app-assets/images/pages/reset-password-v2.svg') }}" img-fluid="img-fluid" alt="Register V2" /></div>
    </div>
    <!-- /Left Text-->
    <!-- Reset password-->
    <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
            <h4 class="card-title mb-1">Reset Password 🔒</h4>
            <p class="card-text mb-2">Your new password must be different from previously used passwords</p>

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
            
            <form class="auth-reset-password-form mt-2" method="POST" action="{{ route('password.update') }}">
            @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ $request->email }}">

                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <label for="reset-password-new">New Password</label>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input class="form-control form-control-merge" id="reset-password-new" type="password" name="password" placeholder="············" aria-describedby="reset-password-new" autofocus="" tabindex="1" />
                        <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <label for="reset-password-confirm">Confirm Password</label>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input class="form-control form-control-merge" id="reset-password-confirm" type="password" name="password_confirmation" placeholder="············" aria-describedby="reset-password-confirm" tabindex="2" />
                        <div class="input-group-append"><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span></div>
                    </div>
                </div>
                <button class="btn btn-primary btn-block" tabindex="3">Set New Password</button>
            </form>
            <p class="text-center mt-2"><a href="{{ route('login') }}"><i data-feather="chevron-left"></i> Back to login</a></p>
        </div>
    </div>
    <!-- /Reset password-->
@endSection
@section('scripts')
<script type="text/javascript">
</script>
@endSection