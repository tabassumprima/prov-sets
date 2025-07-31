<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Services\ChartOfAccountService;
use App\Services\GlCodeService;
use App\Services\LevelService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class ChartOfAccountSeeder extends Seeder
{
    protected $service;
    protected $coaService;
    protected $levelService;
    protected $data;

    public function __construct()
    {
        $this->service = new GlCodeService();
        $this->coaService = new ChartOfAccountService();
        $this->levelService = new LevelService();

        $this->data = array();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('/seeders/data-version2/chartofaccount.csv');
        $dd = (new FastExcel)->import($path);
        $collection  = collect($dd);
        $collection1 = $collection->groupBy(['Level0', 'Level 1', 'Level 2', 'Level 3', 'Level 4']);

        $chartOfAccountLevel = $this->levelService->create("Chart Of Accounts");
        $unallocated = $this->levelService->create("Unallocated");
        // $child[] = [
        //     'gl_code_id' => null,
        //     'level_id' => $this->levelService->create("Unallocated"),
        //     'organization_id' =>4,
        //     'type'  => 'file',
        //     'children' =>[]
        // ];

        ChartOfAccount::create([
                    'gl_code_id' => null,
                    'level_id' => $this->levelService->create("Chart Of Accounts"),
                    'organization_id' =>4,
                    'type'  => 'folder',

                ]);
        ChartOfAccount::create([
                    'gl_code_id' => null,
                    'level_id' => $this->levelService->create("Unallocated"),
                    'organization_id' =>4,
                    'type'  => 'folder',

                ]);
        // foreach($collection1 as $key0 => $level0){
        //     foreach($level0 as $key1 => $level1){
        //         foreach($level1 as $key2 => $level2){
        //             foreach($level2 as $key3 => $level3){
        //                 foreach($level3 as $key4 => $level4){
        //                     $id = Cache::remember($level4[0]['GL Code'], 60, function () use ($level4) {
        //                         return $this->service->getId($level4[0]['GL Code']);
        //                     });
        //                     if($id != null){
        //                         $child4[] = [
        //                             'gl_code_id' => $id,
        //                             'organization_id' => 4,
        //                             'level_id' => $this->levelService->create($key4),
        //                             'type'  => "file",
        //                         ];
        //                     }
        //                 }
        //                 $child3[] = [
        //                     'gl_code_id' =>  null,
        //                     'level_id' => $this->levelService->create($key3),
        //                     'organization_id' =>4,
        //                     'type'  => "folder",
        //                     'children' => $child4
        //                 ];
        //                 $child4 =[];
        //             }
        //             $child2[] = [
        //                 'gl_code_id' => null,
        //                 'level_id' => $this->levelService->create($key2),
        //                 'organization_id' =>4,
        //                 'type'  => "folder",
        //                 'children'=> $child3
        //             ];
        //             $child3 = [];

        //         }
        //         $child1[] = [
        //             'gl_code_id' => null,
        //             'level_id' => $this->levelService->create($key1),
        //             'organization_id' =>4,
        //             'type'  => "folder",
        //              'children' => $child2
        //         ];
        //         $child2 = [];
        //     }
        //     $parent = ChartOfAccount::create([
        //         'gl_code_id' => null,
        //         'level_id' => $this->levelService->create($key0),
        //         'organization_id' =>4,
        //         'type'  => 'folder',
        //         'children' => $child1,
        //     ]);
        //     $child1 = [];
        // }
    }
}
