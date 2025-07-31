<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;
use Carbon\Carbon;
class OtpVerificationEmailController extends Controller
{

    public function index(){
        // when otp unverified
        if (Auth::user()->is_otp_verified == 0) {
            return view('otp.index')->with('dateTime',Carbon::parse(Auth::user()->is_otp_valid)->subMinutes(2));
        }else{
            return redirect()->back();
        }
    }

    public function store(Request $request){
        $this->validate($request,[
            'otp'=>'required|min:6',
        ]);

        $user = Auth::user();
        // check logged in user is exits
        if ($user) {
            // check otp code is Valid
            if(Hash::check($request->otp, $user->otp)){
                // check otp verification time is expire
                if (Carbon::now() <= Carbon::parse($user->is_otp_valid)) {
                    // update otp in database
                    User::where('email',Auth::user()->email)->update(['is_otp_verified' => 1]);

                    // when user is admin
                    if ($user->hasRole('admin')) {
                        return redirect(route('tenant.index'));
                    } else {
                        return redirect(route('user.dashboard'));
                    }
                    
                } else {
                    return redirect()
                    ->back()
                    ->with("error", "Code expired. Click 'Resend Code' for a new one.");
                }
            }
            else{
                return redirect()
                ->back()
                ->with("error", "Code is invalid");
            }
        }

    }
    // Resend Email
    public function resendEmail() {
        // Get auth user
        $user = Auth::user();
        // when is otp verified is 0
        if ($user->is_otp_verified == 0) {
            // when current date is greater than equal to is otp valid
            if (Carbon::now() >= Carbon::parse($user->is_otp_valid)->subMinutes(2)) {
                $user->sendOtp();
                return redirect(route('otpVerify'))
                ->with("success", "Code resent successfully.");
            }
        }else {
            return redirect()->back();
        }
    }
}
