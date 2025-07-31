<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Models\ChartOfAccount;
use App\Models\Level;
use App\Models\ChartOfAccountFile;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Services\OrganizationService;

class ChartOfAccountService
{
    protected $model, $levelService, $userService ;

    public function __construct()
    {
        $this->model = new ChartOfAccount();
        $this->levelService = new LevelService();
        $this->userService = new UserService();
    }

    public function fetchAll()
    {
        $organization_id = $this->userService->fetchAuthOrganizationId();
        return $this->model->scoped(['organization_id' => $organization_id])->get();
    }

    public function fetchColumns(array $columns)
    {
        $organization_id = $this->userService->fetchAuthOrganizationId();
        return $this->model->select($columns)->scoped(['organization_id' => $organization_id])->get();
    }

    public function getId($level)
    {
        return optional($this->model->where('level', $level)->first())->id;
    }

    public function fetchTree()
    {
        $organization_id = $this->userService->fetchAuthOrganizationId();

        $chartOfAccount = $this->model->select('chart_of_accounts.id', 'parent_id as parent',
            DB::raw("CASE
                        WHEN chart_of_accounts.level_id IS NULL THEN gl_codes.code || ' - ' || gl_codes.description
                        ELSE CAST(levels.code AS TEXT)
                    END ||
                    CASE
                        WHEN levels.level IS NULL THEN ''
                        ELSE ' - ' || levels.level
                    END as text
            "),
            'type'
        )
            ->leftJoin('levels', 'levels.id', '=', 'chart_of_accounts.level_id')
            ->leftJoin('gl_codes', 'gl_codes.id', '=', 'chart_of_accounts.gl_code_id')
            ->where('chart_of_accounts.organization_id', $organization_id)->get();

        $chartOfAccounts = $chartOfAccount->map(function ($item) use ($chartOfAccount) {
            $item->id = CustomHelper::encode($item->id);
            if($item->parent != '#')
                $item->parent = CustomHelper::encode($item->parent);

            return $item;
        });

        return $chartOfAccounts;
    }


    /**
     * This function will run only once on ogranization creation
     * function will create default chart of account
     * by default 1 root and 1 child will be created
     */
    public function initCreate($organization_id)
    {
        $root = $this->model->create([
            'gl_code_id' => null,
            'level_id' => $this->levelService->create($organization_id, "Chart Of Accounts","DL00001"),
            'organization_id' => $organization_id,
            'type'  => 'folder',
        ]);

        $child = $this->model->create([
            'gl_code_id' => null,
            'level_id' => $this->levelService->create($organization_id, "Unallocated","DL00002"),
            'organization_id' => $organization_id,
            'type'  => 'folder',
        ]);
        return true;
    }

    public function decodeChartOfAccounts(array $items)
    {
        return collect($items)->map(function ($item) {
            $item['id'] = CustomHelper::decode($item['id']);

            if (!empty($item['parent']) && $item['parent'] != '#') {
                $item['parent'] = CustomHelper::decode($item['parent']);
            }

            if (!empty($item['children']) && is_array($item['children'])) {
                $item['children'] = $this->decodeChartOfAccounts($item['children'])->toArray();
            }

            return $item;
        });
    }

    public function rebuild($request)
    {
        //Organization Id of Auth User
        $userService = new UserService;
        $organization_id = $userService->fetchAuthOrganizationId();
        //node consist (data and new_node)
        $data = $request->input('node');
        //if there's a new child that will be stored in new_node
        $nodes = ($data['new_node'])?? null;

        //check whether node has been renamed
        $rename_nodes = ($data['rename_node']) ?? null;

        //check if any node has been moved
        $move_parent  = ($data['move_parent']) ?? null;
        //invalidate report if folder has been moved
        if($move_parent) {
            $this->invalidateReport($organization_id);
        }
        //check if any node has been renamed
        if($rename_nodes){
            foreach($rename_nodes as $node)
            {
                //rename node i.e level
                $this->rename_node($node['id'], $node['text']);
            }
        }
        /**
         * data has a whole tree including new child
         * but new child will not be created using rebuildTree()
         * so we will create manually and attach to it's parent
         */
        $json = json_decode($data['data'], true);

        //check if any new nodes exists
        if($nodes){
            foreach($nodes as $node){
                //find parent
                $parent = $this->model->find($node['parent']);

                //create child and attach to parent
                $child = $this->model->create([
                    'gl_code_id' => null,
                        'level_id' => $this->levelService->create($organization_id, $node['node']['level'],$node['node']['id']),
                    'organization_id' => $organization_id,
                    'type'  => $node['node']['type'],
                ], $parent);
            }
        }
        $chartOfAccounts = [];
        $chartOfAccounts = $this->decodeChartOfAccounts($json)->toArray();
        //rebuild tree to (rename, delete, move) nodes
        $child = $this->model->scoped(['organization_id' => $organization_id])->rebuildTree($chartOfAccounts, true);
        return $child;
    }

    public function invalidateReport($organization_id){
        $jsonReportService = new FormatJsonService();
        $jsonReportService->invalidateReport($organization_id);
    }

    public function rename_node($id, $new_text)
    {
        $level_Id = $this->model->find($id)->level_id;
        $level = $this->levelService->fetchById($level_Id);
        //restrict user from changing default level name
        if($level->level == "Chart Of Accounts" || $level->level == "Unallocated")
            return;

        $level->level = $new_text;
        $level->save();
    }

    public function destroy($node)
    {
        return $node->delete();
    }

    public function deleteNode($request)
    {
        $node = $this->model->find($request->node['id']);
        return $node->delete();
    }

    public function uploadInitCsv($request)
    {
        $file = $request->chart_of_account_file;
        $columns = ['level_id', 'organization_id', 'id', 'type', 'gl_code_id'];

        //get Organization Storage
        $storagePath = CustomHelper::fetchOrganizationStorage($request->organization_id, 'chart_of_account_files');

        //Store file temporary
        $path = Storage::disk('private')->putFileAs($storagePath,$file,$file->getClientOriginalName());

        // get full path
        $fullPath = Storage::disk('private')->path($path);
        $collection = (new FastExcel())->import($fullPath);
        $this->verifyAllGlExistsInCoa($collection->where('type','file')->pluck('gl_code_id'));

        // upload file on s3 and also save in db
        $this->uploadFile($storagePath, $file, $request->organization_id, $file->getClientOriginalName());

        $levelService = new LevelService();
        $chart_of_account_level = $levelService->fetchLevelByCode(config('constant.chart_of_account_level'));
        $unallocated_level = $levelService->fetchLevelByCode(config('constant.unallocated_level'));

        //get "chart of account" level for organization
        $parent = ChartOfAccount::scoped([ 'organization_id' => $request->organization_id])->select($columns)->where('level_id', $chart_of_account_level->id)->first();
        //get "unallocated" level for orgnization
        $last = ChartOfAccount::scoped([ 'organization_id' => $request->organization_id])->select($columns)->where('level_id', $unallocated_level->id)->first();

        $tree = $this->initParent($parent->toArray(), $last->toArray(), $collection->toArray(),  $request->organization_id);

        ChartOfAccount::scoped([ 'organization_id' => $request->organization_id])->rebuildTree($tree, true);
        
        $settingService = new SettingService();
        $settingService->clearOption('management_expense_level_id', $request->organization_id); //to unset management_expense_level_id bcs new levels are deleted 
        //delete temporary file
        Storage::delete($fullPath);
        $this->levelService->deleteAllExceptInitLevel();
    }

    private function initParent($mainParent, $last, $treeArray, $organization_id){
        $branch = array();
        //assign children to "chart of account" level
        $branch[$mainParent['id']] = $mainParent;
        $branch[$mainParent['id']]['children'] = $this->buildTree($treeArray,  $organization_id);
        //re-assign "unallocated" level on root
        $branch[$last['id']] = $last;
        return $branch;
    }

    private function buildTree(array $elements, $organization_id, $parentId = '') {

        $branch = array();

        foreach ($elements as &$element) {
            if ($element['parent_id'] == $parentId) {
                // dd($element);
                $children = $this->buildTree($elements, $organization_id, $element['id']);
                if ($children) {
                    $element['organization_id'] = $organization_id;
                    $element['children'] = $children;
                }
                else{
                    $element['organization_id'] = $organization_id;
                }
                $branch[$element['id']] = $element;
                //If Glcode not exists then level should fetch or create new
                // or else level should be null if glcode
                if($element['gl_code_id'] == "")
                {
                    $branch[$element['id']]['gl_code_id'] = null;
                    $branch[$element['id']]['level_id'] = $this->levelService->create($organization_id, $element['level'], $element['id'], $element['category']);

                }
                else {
                    $glCode = new GlCodeService();
                    $gl_code_id = $glCode->getId($element['gl_code_id']);
                    if ($gl_code_id){
                        $branch[$element['id']]['gl_code_id'] = $gl_code_id;
                        $branch[$element['id']]['level_id'] = null;
                        $branch[$element['id']]['category'] = $element['category'];
                    }
                    else
                        throw new Exception($element['gl_code_id'] ." Glcode not found. Please insert Glcodes for organization before chart of account");
                }
                unset($branch[$element['id']]['level']);

                unset($branch[$element['id']]['parent_id']);
                unset($branch[$element['id']]['id']);
                unset($element);
            }
        }

        return $branch;
    }

    public function downloadFile()
    {
        $columns = ['chart_of_accounts.id', 'levels.level', 'parent_id', 'gl_codes.code as gl_code' , 'type'];
        $organization_id = $this->userService->fetchAuthOrganizationId();
        $chartOfAccount = $this->model->scoped(['organization_id' => $organization_id])->select($columns)
            ->leftJoin('levels', 'levels.id', '=', 'chart_of_accounts.level_id')
            ->leftJoin('gl_codes', 'gl_codes.id', '=', 'chart_of_accounts.gl_code_id')->get();

        $fileName = "chart_of_account.xlsx";
        $storage = CustomHelper::fetchOrganizationStorage($organization_id, "organization_path");
        $path = $storage.$fileName;
        $path = Storage::disk("private")->path($storage.$fileName);
        return (new FastExcel($chartOfAccount->toArray()))->download($path);

    }

    /**
     * Verify if uploaded chart of account has missing Glcodes.
     *
     * @param \Illuminate\Support\Collection $coa_collection contains collection of uploaded chart of account
     */
    public function verifyAllGlExistsInCoa($coa_collection)
    {
        $gl_services = new GlCodeService;
        $gl_codes = $gl_services->fetchAllWithColumns('code');
        $gl_code_diff = $gl_codes->pluck('code')->diff($coa_collection);
        if ($gl_code_diff->count() > 0)
            throw new Exception("Uploaded file does not contains all Gl Codes");

    }

    public function verifyGlLevels($codes){
        $organization_id = $this->userService->fetchAuthOrganizationId();
        $levels = Level::whereIn('code',$codes)->get()->pluck('id');
        $chartOfAccount = $this->model->scoped(['organization_id' => $organization_id])->get()->pluck('level_id');
        $unmatched_glcode = collect($levels)->diff($chartOfAccount);
        if($unmatched_glcode->count() > 0 )
            throw new Exception("Json report contain bad level id");
    }

    public function getCategory($gl_code_id)
    {
        return $this->model->select('category')->where('gl_code_id', $gl_code_id)->first();
    }

    public function fetchChartOfAccountFilePath($organization_id)
    {
        return  ChartOfAccountFile::where('organization_id',$organization_id)->first();
    }

    // Upload file on s3 and also save data in db
    public function uploadFile($storagePath, $file, $organizationId, $filePath)
    {
        $timestamp = now()->timestamp; // Get the current timestamp
        $originalName = $filePath;
        $filePath = $timestamp . '_' . $originalName;

        Storage::disk('s3')->putFileAs($storagePath, $file, $filePath);

        return ChartOfAccountFile::updateOrCreate(
            [
                'organization_id'   => $organizationId,
            ],
            [
                'path'     => $filePath,
        ]);
    }

    // Download Chart of account file
    public function downloadFileData()
    {
        $organizationService = new OrganizationService();

        $organizationId = $organizationService->getTenantOrganizationId();

        $fileData = $this->fetchChartOfAccountFilePath($organizationId);

        if (!isset($fileData) && empty($fileData)) {
            return null;
        }

        $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'chart_of_account_files');

        $path = $filePath.$fileData->path;

        if (Storage::disk('s3')->exists($path)) {
            return Storage::disk('s3')->download($path);
        }

        return null;
    }

    public function storeNode($nodes,$chart_of_account_id)
    {
        // Organization Id of Auth User
        $userService = new UserService;
        $organization_id = $userService->fetchAuthOrganizationId();

        // Check if any new nodes exist
        if ($nodes) {
            $parent = $this->model->find($chart_of_account_id);

            // Create child and attach to parent
            $child = $this->model->create([
                'gl_code_id' => null,
                'level_id' => $this->levelService->create($organization_id, $nodes['level'], $nodes['dl_code'] ),
                'category' => $nodes['category'],
                'organization_id' => $organization_id,
                'type' => 'folder',
            ], $parent);

            // Return true if child creation is successful
            return $child;
        }

        // Return false if no nodes exist
        return false;
    }

    public function fetchLevelNameById($id)
    {
        $level_data = $this->model->select('category','id', 'level_id')->withWhereHas('level', function($q){
            return $q->select('level', 'id', 'code');
        })->find($id);
        return $level_data;
    }

    public function fetchById($id)
    {
        return ChartOfAccount::find($id);
    }

}
