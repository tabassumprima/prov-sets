<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class CheckActivePlan
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->organization) {
            $organization = $user->organization;

            // Check if the organization has an active subscription
            $hasActiveSubscription = $organization->activePlan()
                ->where('status', true)
                ->where('ends_at', '>', now())
                ->exists();

                if (!$hasActiveSubscription) {
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['email' => 'Please contact administrator for subscription.']);
                }
        }

        return $next($request);
    }
}
