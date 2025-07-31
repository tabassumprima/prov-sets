@extends('layouts.otp-master', ['title' => 'Verify OTP'])
@section('content')
    <div class="card mb-0">
        <div class="card-body">
            <a class="brand-logo" href="javascript:void(0);">
                <img src="/app-assets/images/logo/delta-logo.svg" width="150" />
                <h2 class="brand-text text-primary ml-1"></h2>
            </a>
            <h4 class="mb-1"><b>Two Step Verification 💬</b></h4>
            <p class="text-start mb-2">
                We’ve sent a code to your email. The code will expire in 5 minutes, so please enter it soon.
                {{-- <span class="fw-bold d-block mt-2">******1234</span> --}}
            </p>
            <p class="mb-0"><b>Type your 6 digit security code</b></p>
            <form id="twoStepsForm" method="POST" action="{{ route('otp.store') }}">
                {{ csrf_field() }}
                <div class="mb-1">
                    <div
                        class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
                        <input type="number"
                            class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50  my-2"
                            maxlength="1" autofocus />
                        <input type="number"
                            class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 ml-1 my-2"
                            maxlength="1" />
                        <input type="number"
                            class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 ml-1 my-2"
                            maxlength="1" />
                        <input type="number"
                            class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 ml-1 my-2"
                            maxlength="1" />
                        <input type="number"
                            class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 ml-1 my-2"
                            maxlength="1" />
                        <input type="number"
                            class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 ml-1 my-2"
                            maxlength="1" />
                    </div>
                    <!-- Create a hidden field which is combined by 3 fields above -->
                    <input type="hidden" name="otp" />
                    <x-toast :errors="$errors" />
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100 mb-1">Verify OTP</button>
                <a href="{{ route('logout') }}" class="btn btn-danger d-grid w-100 mb-1"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mr-50"
                        data-feather="power"></i>Logout</a>
                <div class="text-center">
                    <span id="resendBtn">
                        <b> Didn't get the code?</b>
                        <a href="{{ route('resendEmail') }}">
                            <b>Resend</b>
                        </a>
                    </span>
                    <h5 id="counter"></h5>
                </div>
            </form>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('app-assets/js/scripts/pages/user-otp.js') }}"></script>

    <script>
        // Set the date we're counting down to
        var countDownDate = new Date("{{ $dateTime }}").getTime();
        // Update the count down every 1 second
        var x = setInterval(function() {
            // Get today's date and time
            var now = new Date().getTime();
            // Find the distance between now and the count down date
            var distance = countDownDate - now;
            // Time calculations for days, hours, minutes and seconds
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id="counter"
            var counter = document.getElementById("counter")
            counter.innerHTML = "Request again in: " + minutes + "m " + seconds + "s ";
            counter.style.color = "#003399";

            // If the count down is over, write some text
            if (distance < 0) {
                clearInterval(x);
                // Hide resend function
                document.getElementById("counter").innerHTML = "";
                // unset resend button
                document.getElementById("resendBtn").style.display = "unset";
            }
        }, 1000);
    </script>
@endsection
