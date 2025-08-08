<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Services\OrganizationService;
use Illuminate\Database\Seeder;
use Exception;

class newPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizationService = new OrganizationService();
        $organizations       = $organizationService->fetchAll();

         // Hardcoded permissions array
        $permissions = [
            'Actuarial' => [
                 //gmm discount rate
                'Discount Rates Gmm' => [
                    'view-discount-rate-gmm',
                ],
                //gmm discount rate file
                'Discount Rates File Gmm' => [
                    'view-discount-rate-file-gmm',
                ],
            ],
        ];

        foreach ($organizations as $organization) {
            foreach($permissions as $modulType => $moduleTypes){
                foreach ($moduleTypes as $module => $modulePermissions) {
                    try {
                        foreach ($modulePermissions as $permission) {
                            $data = [
                                'name' => $permission,
                                'organization_id' => $organization->id,
                                'module' => $module,
                                'module_type' => $modulType,
                            ];
                            Permission::create($data);
                        }
                    } catch (Exception $e) {
                        $this->command->info('Info: ' . $e->getMessage());
                    }
                }

            }
        }
    }
}
