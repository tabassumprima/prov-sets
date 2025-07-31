<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'name' => 'Basic',
            'description' => 'Basic plan description.',
            'price' => 0.00,
            'duration_in_days' => 30,
            'duration_in_text' => '1 Month',
            'status' => true,
        ]);

        Plan::create([
            'name' => 'Quaterly',
            'description' => 'Quaterly plan description.',
            'price' => 0.00,
            'duration_in_days' => 90,
            'duration_in_text' => '3 Months',
            'status' => true,
        ]);
    }
}
