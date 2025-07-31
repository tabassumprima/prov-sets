<?php

namespace App\Http\Middleware;

use App\Support\Google2FAAuthenticator;
use Closure;
use Auth;

class Google2FAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get auth user
        $user = Auth::user();
        $admin = app('impersonate');

        // Check Verification Type
        if($user->verification_type == 'email'){
            //check impersonating
            if (!$admin->isImpersonating()) {
                // check otp code is exit
                if (!$user->verifyOtp()) {
                    return redirect(route('otpVerify'));
                }
            }
            return $next($request);
            
        }
        else if ($user->verification_type == null) {
            return $next($request);
        }
        else{
            $authenticator = app(Google2FAAuthenticator::class)->boot($request);
    
            if ($authenticator->isAuthenticated() || $admin->isImpersonating()) {
                return $next($request);
            }
    
            return $authenticator->makeRequestOneTimePasswordResponse();

        }
    }
}
