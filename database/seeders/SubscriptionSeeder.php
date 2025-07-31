<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Organization;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans         = Plan::all();
        $features      = Feature::all();
        $organizations = Organization::all();

        foreach ($plans as $plan) {

            // Attach features to the plan
            foreach ($features as $feature)
                $plan->features()->attach($feature->id);

            foreach ($organizations as $organization) {
                $subscription = Subscription::create([
                    'organization_id' => $organization->id,
                    'plan_id'         => $plan->id,
                    'starts_at'       => now(),
                    'ends_at'         => now()->addDays($plan->duration_in_days),
                    'status'          => true,
                ]);

                // Attach random features to the subscription with usage counts
                // $subscription->features()->attach(
                //     $features->random(rand(1, 3))->pluck('id')->toArray(),
                //     ['used' => rand(1, 10)]
                // );
            }
        }
    }
}
