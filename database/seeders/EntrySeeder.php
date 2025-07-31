<?php

namespace Database\Seeders;

use App\Models\EntryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entry_types = [
            [
                "type" => "Org",
                "description"   => 'Original'
            ],
            [
                "type" => "Org-R",
                "description"   => 'Original Reversed'
            ],
            [
                "type" => "delta",
                "description"   => 'Delta'
            ],

        ];

        EntryType::insert($entry_types);

    }
}
