<?php 

namespace App\Services;

use App\Models\Journal;
use Illuminate\Support\Facades\Auth;

class JournalService{

    protected $model;

    public function __construct()
    {
        $this->model = new Journal();
    }

    public function create($request)
    {
        extract($request->toArray());
        $systemDepartmentId = $this->getSystemDeparmentId($profit_center_id);
        $journal =  $this->model->create([
            'branch_info_id'        => $branch_info_id,
            'voucher_type_id'       => $voucher_type_id,
            'accounting_year_id'    => $accounting_year_id,
            'profit_center_id'      => $profit_center_id,
            'system_narration1'     => $system_narration1,
            'business_type_id'      => $business_type_id,
            'system_date'           => $system_date,
            'organization_id'       => $this->fetchOrganizationId($systemDepartmentId),
            'system_department_id'  => $systemDepartmentId,
            'transaction_type_id'   => $this->fetchTransactionTypeId("M"),
            'entry_type_id'         => $this->fetchEntryTypeId("M", "type"),
            'voucher_number'        => $this->generateVoucherNumber(),
            'created_by'            => Auth::user()->id,
        ]);
        return $journal;
    }

    public function fetchAll()
    {
        return $this->model->all();
    }

    public function fetchTransactionTypeId($type)
    {
        $transactionTypeService = new TransactionTypeService();
        return $transactionTypeService->getId($type);
    }

    public function fetchEntryTypeId($entryType, $type)
    {
        $entryTypeService = new EntryTypeService();
        return $entryTypeService->getId($entryType, $type);
    }


    public function fetchOrganizationId($systemDepartmentId)
    {
        $systemDepartmentService = new SystemDepartmentService();       
        return $systemDepartmentService->fetchColumns('organization_id', $systemDepartmentId)->organization_id;
    }

    public function getSystemDeparmentId($profit_center_id)
    {
        $profitCenterService = new ProfitCenterService();
        $code = $profitCenterService->fetchColumns('code', $profit_center_id)->code;
        
        return substr($code, -2);
    }

    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function generateVoucherNumber()
    {
        return str_pad(rand(0, pow(10, 6)-1), 6, '0', STR_PAD_LEFT);
    }

    public function getId($voucher)
    {
        return $this->model->where('voucher_number', $voucher)->first()->id;
    }
}