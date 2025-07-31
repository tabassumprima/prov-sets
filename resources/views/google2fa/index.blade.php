@extends('layouts.otp-master', ['title' => 'Verify 2fa'])
@section('content')
<div class="card mb-0">
    <div class="card-body">
        <a class="brand-logo" href="javascript:void(0);">
            <img  src="/app-assets/images/logo/delta-logo.svg" width="150"/>
            <h2 class="brand-text text-primary ml-1"></h2>
        </a>
        <h4 class="mb-1"><b>Two Step Verification 💬</b></h4>
        <p class="text-start mb-2">To enhance security, kindly enter the two-factor authentication code sent to your registered authenticator. Thank you!</p>
        <p class="mb-0"><b>Type your 6 digit security code</b></p>
        <form  id="twoStepsForm" method="POST" action="{{ route('2fa') }}">
            {{ csrf_field() }}
        <div class="mb-1">
            <div
            class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper"
            >
            <input
                type="number"
                class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50  my-2"
                maxlength="1"
                autofocus
            />
            <input
                type="number"
                class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 ml-1 my-2"
                maxlength="1"
            />
            <input
                type="number"
                class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 ml-1 my-2"
                maxlength="1"
            />
            <input
                type="number"
                class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 ml-1 my-2"
                maxlength="1"
            />
            <input
                type="number"
                class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 ml-1 my-2"
                maxlength="1"
            />
            <input
                type="number"
                class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 ml-1 my-2"
                maxlength="1"
            />
            </div>
            <!-- Create a hidden field which is combined by 3 fields above -->
            <input type="hidden" name="one_time_password" />
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-body">
                            {{ $error }}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="submit" class="btn btn-primary d-grid w-100 mb-1">Verify OTP</button>
        <a href="{{ route('logout') }}" class="btn btn-danger d-grid w-100 mb-1" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mr-50" data-feather="power"></i>Logout</a>
        </form>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('app-assets/js/scripts/pages/user-2fa.js')}}"></script>
@endsection
