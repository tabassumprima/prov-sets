<?php

namespace App\Http\Controllers\User;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\{ChartOfAccountService, LevelService, OrganizationService};
use App\Traits\CheckPermission;
use Illuminate\Http\Request;    
use Illuminate\Support\Facades\{DB, Log};
use App\Http\Requests\Level\Request as LevelRequest;
use App\Helpers\CustomHelper;

class ChartOfAccountController extends Controller
{
    use CheckPermission;

    private $chartOfAccountService, $router, $routerHelper , $levelService, $organizationService;

    public function __construct(ChartOfAccountService $chartOfAccountService)
    {
        $this->router = 'groups.index';
        $this->chartOfAccountService = $chartOfAccountService;
        $this->routerHelper = new RouterHelper;
        $this->levelService = new LevelService;
        $this->organizationService = new OrganizationService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-chart-of-account');
        $chartOfAccounts = $this->chartOfAccountService->fetchTree();
        return view('user.chart-of-accounts.view', compact('chartOfAccounts'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->toArray());
        DB::beginTransaction();
        try {
           $id = $this->chartOfAccountService->create($request);
            DB::commit();
            return $id;
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
            DB::rollBack();
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function rebuild(Request $request){

        $message ='Inserted';
        try {
            $child = $this->chartOfAccountService->rebuild($request);

        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);

        }
        return $message;
    }

    public function deleteNode(Request $request){
       return $this->chartOfAccountService->deleteNode($request);
    }

    public function getFile()
    {
       return $this->chartOfAccountService->downloadFile();
    }

    public function create($parentId)
    {
        $chartOfAccount_id =   CustomHelper::decode($parentId);
        $coa = $this->chartOfAccountService->fetchLevelNameById($chartOfAccount_id);
       
       
        $level_name = $coa->level->level;
       // View for add new chart of account level 
        return view('user.levels.create', compact('parentId','level_name'));
    }

    public function storeLevel(LevelRequest $LevelRequestData, $chart_of_Account_id)
    {
        $chart_of_Account_id =   CustomHelper::decode($chart_of_Account_id);
        $result = $this->chartOfAccountService->storeNode($LevelRequestData, $chart_of_Account_id);

        // Redirect based on the service method result
        if ($result) {
            return redirect()->route('chart-of-accounts.index')->with('success', 'Level created successfully!');
        }

        // Redirect back with an error message if creation failed
        return redirect()->back()->with('error', 'Failed to create level.');
    }

    public function editLevel($chartOfAccountId)
    {
        $orgnizationId =  $this->organizationService->getAuthOrganizationId();
        $chartOfAccount_id =   CustomHelper::decode($chartOfAccountId);
        $chartOfAccounts = new ChartOfAccountService();
        $coa = $this->chartOfAccountService->fetchLevelNameById($chartOfAccount_id);
        $level_data = $coa->level;

        return view('user.levels.edit', compact('level_data', 'coa'));
    }

    public function updateLevel(LevelRequest $LevelRequestData, $chartOfAccountId, $levelId)
    {   
        DB::beginTransaction(); // Begin the transaction in the controller

       
        try
        {
            $level = $this->levelService->fetchById($levelId);

            if ($level) {
                $level->level = $LevelRequestData->input('level');
                $level->save();
            }

            // Update category in chart of account
            $chartOfAccount = $this->chartOfAccountService->fetchById($chartOfAccountId);
            if ($chartOfAccount) {
                $chartOfAccount->category = $LevelRequestData->input('category');
                $chartOfAccount->save();
            }
            // $this->levelService->updateLevelNameById($chartOfAccountId, $LevelRequestData);

            DB::commit();
            return redirect()->route('chart-of-accounts.index')->with('success', 'Level updated successfully!');

        }
        catch(\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update level. Error: ' . $e->getMessage());
        }

        
    }
}
