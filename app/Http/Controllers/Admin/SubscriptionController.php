<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\{OrganizationService, PlanService, SubscriptionService};
use Illuminate\Support\Facades\Log;
use Exception;
use App\Http\Requests\SubscriptionPlan\Request;

class SubscriptionController extends Controller
{
    private $routerHelper;

    public function __construct()
    {
        $this->routerHelper = new RouterHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizationService = new OrganizationService();
        $organization        = $organizationService->fetch(request()->org, ['activePlan']);

        $planService = new PlanService();
        $plans       = $planService->fetchActivePlans();

        return view('admin.subscriptions.index', compact('organization', 'plans'));
    }

    public function store(Request $request)
    {
        $error   = false;
        $message = 'Subscription updated succesfully.';

        try {
            $subscriptionService = new SubscriptionService();


             // Validate if subscription_id is null
            if ( $request->subscription_plan < 1 ) {
                throw new \Exception("Subscription plan is required.");
            }

            if ($request->active_plan != $request->subscription_plan) {
                if ($request->subscription_id)
                    $subscriptionService->cancelSubscription($request->subscription_id);
                $subscription = $subscriptionService->addSubscription($request->organization_id, $request->toArray());
                $request['subscription_id'] = $subscription->id;
            }

            if ($request->add_extra_days)
                $subscriptionService->addDaysInCurrentSubscriptionPlan($request->toArray());
        } catch (Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        return $this->routerHelper->redirectBack($error, $message);
    }

    public function destroy($id)
    {
        $error   = false;
        $message = 'Subscription cancel succesfully.';

        try {
            $subscriptionService = new SubscriptionService();
            $subscriptionService->cancelSubscription($id);
        } catch (Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        return $this->routerHelper->redirectBack($error, $message);
    }
}
