<?php

namespace App\Services;

use App\Models\Organization;
use App\Helpers\CustomHelper;
use App\Helpers\DynamoHelper;
use Illuminate\Support\Facades\{Auth, Storage};

class OrganizationService
{
    protected $model, $userService;
    public function __construct()
    {
        $this->model = new Organization();
        $this->userService = new UserService();
    }

    public function create($request)
    {
        $encoded_tenant_id = base_convert($request->shortcode.uniqid(), 16, 10);

        $this->insertDynamoDb($encoded_tenant_id, $request->shortcode);

        $request      = $request->merge(['tenant_id' => $encoded_tenant_id]);
        $organization = $this->model->create($request->all());

        // create dashboard files
        $this->initCreateDashboardFile($organization->id);
        $this->copyDependentFile($organization->id);
        return $organization;
    }

    public function update($request, $id)
    {
        $organization = $this->fetch($id);
        $organization->fill($request->all())->save();
        return $organization;
    }

    public function delete($id)
    {
        $organization = $this->fetch($id);
        return $organization->delete();
    }

    public function fetch($id, $relations = array())
    {
        return $this->model->with($relations)->findOrFail(CustomHelper::decode($id));
    }

    public function exists($id)
    {
        return $this->model->where('id', (CustomHelper::decode($id)))->exists();
    }

    public function fetchWithRelation($id, $relation = array())
    {
        return $this->model->with($relation)->findOrFail(CustomHelper::decode($id));
    }

    public function fetchAll()
    {
        return $this->model->all();
    }

    public function getPaginateData($search)
    {
        if (isset($search) && $search != null && !empty($search)) {
           $data = $this->model->where('name',  'ilike', '%' . $search . '%');
        }else{
            $data = $this->model;
        }
        // get pagination value from config
        $paginationValue = config('constant.organization_pagination');
        return $data->paginate($paginationValue);
    }

    public function getAuthOrganizationId()
    {
        return Auth::user()->organization_id;
    }

    public function getAuthTenantId($organization_id)
    {
        return Auth::user()->organization->tenant_id;
    }

    public function countUnapproveProvision()
    {
        $status_id = CustomHelper::fetchStatus('completed', 'provision');
        return $this->model->withCount(['import_details' => function($q) use ($status_id) {
            $q->where(['isLocked' => 0, 'status_id' => $status_id, 'type' => 'provision']);
        }])->findOrFail($this->getAuthOrganizationId())->provision_count;
    }

    /*  deprecated function
        Functionality: show alert if there are unapprove journal entries
        Logic: return journal entries count if approved_by column is null
        deprecate reason: page loading time is 3+s
    */

    // public function countUnapproveJournalEntries()
    // {
    //     $status_id = CustomHelper::fetchStatus('completed', 'provision');
    //     return $this->model->withCount(['journalEntries' => function($q) use ($status_id) {
    //         $q->whereNull('approved_by');
    //     }])->findOrFail($this->getAuthOrganizationId())->journal_entries_count;
    // }

    /*
        This function is a new version of countUnapproveJournalEntries()
        Functionality: show alert if there are unapprove journal entries
        Logic: return journal entries if approved_by column is null with limit 1,
        limitation will improve loading time
    */
    // public function countUnapproveJournalEntries()
    // {
    //     $status_id = CustomHelper::fetchStatus('completed', 'posting');
    //     return dd($this->model->withCount(['import_details' => function($q) use ($status_id) {
    //         $q->where(['isLocked' => 0, 'status_id' => $status_id, 'type' => 'manual']);
    //     }])->findOrFail($this->getAuthOrganizationId()));
    // }

    public function generateOrganizationLogoName($originalName)
    {
        $name = explode('.', $originalName->getClientOriginalName());
        return "logo." . last($name);
    }

    public function saveLogo($organization, $logo)
    {
        //Generate Logo Filename
        $logoFileName = $this->generateOrganizationLogoName($logo);

        //Update logo file name;
        $organization->logo = $logoFileName;
        $organization->save();

        $storagePath = CustomHelper::fetchOrganizationStorage($organization->id, 'logo');
        Storage::disk('s3')->putFileAs($storagePath, $logo, $logoFileName);
    }

    public function verifyIfProvisionFilesAreEmpty()
    {
        $organization_id = $this->getAuthOrganizationId();
        $files = $this->model->withCount(['activeDiscountRates', 'activeIbnrAssumptions', 'activeRiskAdjustments'])->findOrFail($organization_id);
        if($files->active_discount_rates_count == 0 || $files->active_ibnr_assumptions_count == 0 || $files->active_risk_adjustments_count == 0 )
            return false;
        return true;
    }

    public function toggleBoarding($id)
    {
        $this->model = $this->fetch($id);
        if($this->model->isBoarding)
            $this->model->isBoarding = false;
        else
            $this->model->isBoarding = true;
        $this->model->save();
        return $this->model;
    }

    public function isBoarding()
    {
        $organization = $this->fetch(CustomHelper::encode($this->getAuthOrganizationId()));
        return $organization->isBoarding;
    }

    public function tenantIsBoarding()
    {
        $organization = $this->fetch(request('org'));
        return $organization->isBoarding;
    }

    public function getTenantId($organization_id)
    {
        $organization = $this->fetch(CustomHelper::encode($organization_id));
        return $organization->tenant_id;
    }

    public function getTenantOrganizationId()
    {
        $organization = $this->fetch(request('org'));
        return $organization->id;
    }

    public function hasRelation($relation)
    {
        return $this->model->has($relation);
    }

    public function initCreateDashboardFile($organization_id)
    {
        $default_reports     = ['cities-data.json', 'chart-data.json', 'new_graph.json'];
        $adminStorage        = CustomHelper::fetchAdminStorage('dashboard');
        $organizationStorage = CustomHelper::fetchOrganizationStorage($organization_id, 'dashboard');
        foreach($default_reports as $report) {
            Storage::disk('s3')->copy($adminStorage . $report, $organizationStorage . $report);
        }
    }

    public function copyDependentFile($organization_id)
    {
        $file = 'dependent-tables.json';
        $organizationStorage = CustomHelper::fetchOrganizationStorage($organization_id, 'dependency');
        $adminStorage = CustomHelper::fetchAdminStorage('dependency');
        Storage::disk('s3')->copy($adminStorage . $file, $organizationStorage . $file);
    }

    public function insertDynamoDb($tenant_id, $shortcode)
    {
        $dynamodb = new DynamoHelper;
        $items = $dynamodb->itemMapping($tenant_id, $shortcode);
        $dynamodb->dynamoInsert($items);
    }
}
