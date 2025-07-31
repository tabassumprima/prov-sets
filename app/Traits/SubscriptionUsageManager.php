<?php

namespace App\Traits;

use App\Models\Feature;

trait SubscriptionUsageManager
{
    public function checkSubscriptionUsage($featureSlug, int $amount = 1)
    {
        $subscription = $this->activePlan()->first();

        if (!$subscription) {
            //when no active subscription is found
            return redirect()->back()->with('error', 'Please contact administrator for subscription.');
        }

        $feature      = Feature::with([
            'plans' => function ($q) use ($subscription) {
                $q->where('plans.id', $subscription->plan_id);
            }
        ])->where('slug', $featureSlug)->first();

        $usage = $subscription->features()->firstWhere('features.id', $feature->id);
        if (!$usage) {
            $subscription->features()->attach($feature, ['used' => $amount]);
        } else {
            $usagePivot = $usage->pivot;
            $newUsage   = $usagePivot->used + $amount;
            $limit      = $feature->plans->first()->pivot->limit;

            if ($limit != -1 && $newUsage > $limit) {
                // Handle error when usage limit is exceeded
                return redirect()->back()->with('error', 'Usage limit exceeded.');
            }

            $subscription->features()->updateExistingPivot($feature->id, ['used' => $newUsage]);
        }

        return true;
    }
}
