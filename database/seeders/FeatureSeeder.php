<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Feature::create([
            'name' => 'Max User limit',
            'slug' => 'max-user',
            'description' => 'Maximum user account creation limit',
            'status' => true,
        ]);

        Feature::create([
            'name' => 'Provision Run',
            'slug' => 'provision-run',
            'description' => 'Provision Run',
            'status' => true,
        ]);
    }
}
