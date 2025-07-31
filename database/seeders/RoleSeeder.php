<?php

namespace Database\Seeders;

use App\Services\OrganizationService;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin role create
        Role::create(['name' => 'admin']);

        //Other roles
        $data  = array();
        $roles = explode('|', config('constant.default_roles'));

        $organizationService = new OrganizationService();
        $organizations       = $organizationService->fetchAll();
        foreach ($organizations as $organziation) {
            foreach ($roles as $role) {
                Role::create([
                    'name'       => $role,
                    'organization_id' => $organziation->id
                ]

                );
            }
        }
    }
}
