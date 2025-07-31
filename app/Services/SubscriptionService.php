<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionService
{
    public function addSubscription($organizationId, $data)
    {
        extract($data);
        // Find the plan
        $plan = Plan::findOrFail($subscription_plan);

        // Calculate the subscription start and end dates
        $currentDate = Carbon::now();
        $startsAt    = $currentDate->toDateTimeString();
        $endsAt      = $currentDate->addDays($plan->duration_in_days)->endOfDay()->toDateTimeString();

        // Create the subscription
        $subscription = new Subscription();
        $subscription->organization_id = $organizationId;
        $subscription->plan_id         = $plan->id;
        $subscription->starts_at       = $startsAt;
        $subscription->ends_at         = $endsAt;
        $subscription->status          = true; // Assuming status is set to true by default
        $subscription->save();
        return $subscription;
    }

    public function cancelSubscription($id)
    {
        return Subscription::findOrFail($id)->update(['status' => false]);
    }

    public function addDaysInCurrentSubscriptionPlan($data)
    {
        extract($data);
        $subscription = Subscription::findOrFail($subscription_id);
        $currentEndDate = $subscription->ends_at;
        $newEndDate = Carbon::parse($currentEndDate)->addDays($add_extra_days);
        $subscription->ends_at = $newEndDate;
        $subscription->save();
        return $subscription;
    }
}
