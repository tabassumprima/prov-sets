<?php

namespace App\Http\Livewire;

use App\Helpers\CustomHelper;
use App\Helpers\ProvisionHelper;
use App\Jobs\ImportDetail as ImportJob;
use App\Models\ImportDetail;
use App\Services\ImportDetailService;
use App\Services\LambdaFunctionService;
use App\Services\OrganizationService;
use App\Services\StepFunctionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Provision extends Component
{
    public ImportDetail $import;
    public $type = "sync";
    public $lastSync;
    public $lastProvision;
    public $provisionAllowed;
    public $valuation_date;

    protected $rules = [
        'type' => 'required'
    ];

    public function save(){

        $this->validate();
        $organiztionService = new OrganizationService;
        $status_id = CustomHelper::fetchStatus('started','provision');
        $importDetailService = new ImportDetailService();
        $values = [
            'type'                  => 'provision',
            'status_id'             => $status_id,
            'identifier'            => 'provision',
            'organization_id'       =>  $organiztionService->getAuthOrganizationId()
        ];
        $importDetail = $importDetailService->create($values);

        $provision = new ProvisionHelper($this->valuation_date, $importDetail->id);
        $payload = $provision->invokeProvision();

        ImportJob::dispatch(json_encode($payload));

        // $this->import->save();
    }

    public function render()
    {
        return view('livewire.provision');
    }
}
