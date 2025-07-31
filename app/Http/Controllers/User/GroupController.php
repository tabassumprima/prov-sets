<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\GroupRequest;
use Illuminate\Support\Facades\Log;
use App\Services\{GroupService, CriteriaService, OrganizationService, StatusService};
use App\Helpers\RouterHelper;
use App\Models\Criteria;
use App\Traits\CheckPermission;
use Carbon\Carbon;

class GroupController extends Controller
{
    use CheckPermission;

    private $groupService, $router, $routerHelper;
    public function __construct(GroupService $groupService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);

        $this->router       = 'group.index';
        $this->groupService = $groupService;
        $this->routerHelper = new RouterHelper();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $this->authorizePermission('view-' . $type . '-group');
        $OrganizationService = new OrganizationService();
        $criteriaService = new CriteriaService();
        $criterias       = $criteriaService->fetchColumns(['*'], $type);
        $unsortedPortfolioGroups = $this->groupService->fetchAll();
        $order = ['expired' => 3, 'started' => 2, 'not-started' => 1];
        $portfolioGroups = $unsortedPortfolioGroups->sortByDesc(fn ($item) => $order[$item->status->slug])->values();
        $isBoarding = $OrganizationService->isBoarding();
        $groupCount      = $criteriaService->groupCount($type);

        return view('user.groups.index', compact('portfolioGroups', 'criterias', 'groupCount', 'type','isBoarding'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        $error  = false;
        $message = trans('user/group.created');
        $this->authorizePermission('create-' . $request->applicable_to . '-group');

        try {
            $this->groupService->create($request);

            $statusService = new StatusService;
            $expired = $statusService->fetchStatusByModelSlug('group', 'expired')->id;

            $this->groupService->updateEndingGroup(Carbon::now()->startOfDay()->toDateString(), $expired);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }

        $params = ['type' => $request->applicable_to];
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message, $params);
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
    public function update(GroupRequest $request, $id)
    {
        $error   = false;
        $message = trans('user/group.updated');
        try {
            $this->groupService->update($request, $id);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        $params = ['type' => $request->applicable_to];
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message, $params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $error   = false;
        $message = trans('user/group.deleted');

        try {
            $group = $this->groupService->fetch($id);
            $this->authorizePermission('delete-' . $group->applicable_to . '-group');

            $this->groupService->delete($id);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        return $this->routerHelper->redirectBack($error, $message);
    }

    public function generateGroupCode()
    {
        $error = false;
        $message = "Group code mapping generated";
        try {
            $this->groupService->groupCodeMapping();
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }
}
