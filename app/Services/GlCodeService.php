<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Models\GlCode;

class GlCodeService
{
    protected $model;
    public function __construct()
    {
        $this->model = new GlCode();
    }

    public function fetchAll()
    {
        return $this->model->all();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function fetchWithRelation($id, $relations = [])
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    public function fetchAllWithRelation($relations = [])
    {
        return $this->model->with($relations)->get();
    }

    public function fetchAllWithColumns($columns)
    {
        return $this->model->select($columns)->get();
    }

    public function getId($code)
    {
        return optional($this->model->where('code', $code)->first())->id ;
    }

    // Journal Entries sum with glcode groupby
    public function fetchGlCodeSum($glcodes,  $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = 'All', $headOffice_id = null)
    {
        return $this->model->selectRaw('sum(transaction_amount) as transaction, gl_codes.id, gl_codes.code, gl_codes.description, gl_codes.account_type')
        ->leftJoin('journal_entries as je', 'je.gl_code_id', '=', 'gl_codes.id')
        ->leftJoin('journal_mappings as jm', 'jm.journal_entries_id', '=', 'je.id')
        ->whereIn('gl_codes.id', $glcodes)
        ->whereBetween('je.system_date',[$start_date,$end_date])
        ->when($accounting_year , function($query) use ($accounting_year) {
            $query->where('je.accounting_year_id',$accounting_year);
        })
        ->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('je.business_type_id', $business_type_id);
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('je.branch_id', $branch_id);
        })
        ->when($portfolio_id, function ($q) use ($portfolio_id, $headOffice_id) {
            $q->when($portfolio_id != 'All', function ($q) use ($portfolio_id, $headOffice_id) {
                $q->when($portfolio_id == $headOffice_id, function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('portfolio_id', $portfolio_id)->orWhereNull('portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('portfolio_id', $portfolio_id);

                });
            });
        })
        ->groupBy('gl_codes.id', 'gl_codes.code', 'gl_codes.description', 'gl_codes.account_type')->get();
    }

    // Journal Entries Sum with portfolio groupby
    public function fetchGlCodeSumPortfolios($glcodes,  $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = null, $headOffice_id = null, $portfolio_name)
    {
        return $this->model->selectRaw('sum(transaction_amount) as transaction, gl_codes.id, gl_codes.code, gl_codes.description, gl_codes.account_type , p.name as portfolio_name, coalesce(p.shortcode,?) as shortcode ', [$portfolio_name])
        ->leftJoin('journal_entries as je', 'je.gl_code_id', '=', 'gl_codes.id')
        ->leftJoin('journal_mappings as jm', 'jm.journal_entries_id', '=', 'je.id')
        ->leftJoin('portfolios as p', 'jm.portfolio_id', '=', 'p.id')
        ->whereIn('gl_codes.id', $glcodes)
        ->whereBetween('je.system_date',[$start_date,$end_date])
        ->when($accounting_year , function($query) use ($accounting_year) {
            $query->where('je.accounting_year_id',$accounting_year);
        })
        ->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('je.business_type_id', $business_type_id);
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('je.branch_id', $branch_id);
        })
        ->when($portfolio_id, function ($q) use ($portfolio_id, $headOffice_id) {
            $q->when($portfolio_id != 'All', function ($q) use ($portfolio_id, $headOffice_id) {
                $q->when($portfolio_id == $headOffice_id, function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('jm.portfolio_id', $portfolio_id)->orWhereNull('jm.portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('jm.portfolio_id', $portfolio_id);

                });
            });
        })
        ->groupBy('gl_codes.id', 'gl_codes.code', 'gl_codes.description', 'gl_codes.account_type', 'shortcode' , 'portfolio_name')->get();
    }

    // Journal Entries Sum with credit and debit
    public function fetchGLCodeWithCreditDebit($glcodes,  $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = 'All', $headOffice_id = null)
    {
        return $this->model->selectRaw('
        SUM(CASE WHEN je.transaction_amount < 0 THEN je.transaction_amount ELSE 0 END) AS credit,
        SUM(CASE WHEN je.transaction_amount >= 0 THEN je.transaction_amount ELSE 0 END) AS debit,
        gl_codes.id, gl_codes.code, gl_codes.description')
        ->leftJoin('journal_entries as je', 'je.gl_code_id', '=', 'gl_codes.id')
        ->leftJoin('journal_mappings as jm', 'jm.journal_entries_id', '=', 'je.id')
        ->whereIn('gl_codes.id', $glcodes)
        ->whereBetween('je.system_date',[$start_date,$end_date])
        ->when($accounting_year , function($query) use ($accounting_year) {
            $query->where('je.accounting_year_id',$accounting_year);
        })
        ->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('je.business_type_id', $business_type_id);
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('je.branch_id', $branch_id);
        })
        ->when($portfolio_id, function ($q) use ($portfolio_id, $headOffice_id) {
            $q->when($portfolio_id != 'All', function ($q) use ($portfolio_id, $headOffice_id) {
                $q->when($portfolio_id == $headOffice_id, function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('jm.portfolio_id', $portfolio_id)->orWhereNull('jm.portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('jm.portfolio_id', $portfolio_id);

                });
            });
        })
        ->groupBy('gl_codes.id', 'gl_codes.code', 'gl_codes.description')->get();
    }

    // Journal Entries Sum with credit and debit groupby portfolios for csv
    public function fetchGLCodeWithCreditDebitPortfolios($glcodes,  $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = null, $headOffice_id, $portfolio_name)
    {
        return $this->model->selectRaw('
        SUM(CASE WHEN je.transaction_amount < 0 THEN je.transaction_amount ELSE 0 END) AS credit,
        SUM(CASE WHEN je.transaction_amount >= 0 THEN je.transaction_amount ELSE 0 END) AS debit,
        gl_codes.id, gl_codes.code, gl_codes.description, gl_codes.account_type, p.name as portfolio_name , coalesce(p.shortcode,?) as shortcode', [$portfolio_name])
        ->leftJoin('journal_entries as je', 'je.gl_code_id', '=', 'gl_codes.id')
        ->leftJoin('journal_mappings as jm', 'jm.journal_entries_id', '=', 'je.id')
        ->leftJoin('portfolios as p', 'jm.portfolio_id', '=', 'p.id')
        ->whereIn('gl_codes.id', $glcodes)
        ->whereBetween('je.system_date',[$start_date,$end_date])
        ->when($accounting_year , function($query) use ($accounting_year) {
            $query->where('je.accounting_year_id',$accounting_year);
        })
        ->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('je.business_type_id', $business_type_id);
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('je.branch_id', $branch_id);
        })
        ->when($portfolio_id, function ($q) use ($portfolio_id, $headOffice_id) {
            $q->when($portfolio_id != 'All', function ($q) use ($portfolio_id, $headOffice_id) {
                $q->when($portfolio_id == $headOffice_id, function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('jm.portfolio_id', $portfolio_id)->orWhereNull('jm.portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('jm.portfolio_id', $portfolio_id);

                });
            });
        })
        ->groupBy('gl_codes.id', 'gl_codes.code', 'gl_codes.description', 'gl_codes.account_type', 'shortcode', 'portfolio_name')->get();
    }

    // Opening balances sum
    public function fetchGlCodeOpening($glcodes, $accounting_year, $headOffice_id, $branch_id, $business_type_id = "All", $portfolio_id = 'All')
    {
        return $this->model->selectRaw('sum(balance) as opening, gl_codes.id, gl_codes.code, gl_codes.description, gl_codes.account_type')
        ->leftJoin('opening_balances as ob', 'ob.gl_code_id', '=', 'gl_codes.id')
        ->leftJoin('opening_balance_mappings as obm', 'obm.opening_balance_id', '=', 'ob.id')
        ->whereIn('gl_codes.id', $glcodes)
        ->when($accounting_year , function($query) use ($accounting_year) {
            $query->where('ob.accounting_year_id',$accounting_year);
        })
        ->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('ob.business_type_id', $business_type_id);
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('ob.branch_id', $branch_id);
        })
        ->when($portfolio_id, function ($q) use ($portfolio_id, $headOffice_id) {
            $q->when($portfolio_id != 'All', function ($q) use ($portfolio_id, $headOffice_id) {
                $q->when($portfolio_id == $headOffice_id, function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('obm.portfolio_id', $portfolio_id)->orWhereNull('obm.portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('obm.portfolio_id', $portfolio_id);

                });
            });
        })
        ->groupBy('gl_codes.id', 'gl_codes.code', 'gl_codes.description', 'gl_codes.account_type')->get();

    }
    // Opening balances groupby portfolio for csvz
    public function fetchGlCodeWithPortfoliosOpening($glcodes, $accounting_year, $branch_id, $headOffice_id, $business_type_id = "All", $portfolio_id = 'All', $portfolio_name)
    {
        // dd($glcodes, $accounting_year, $branch_id, $headOffice_id, $business_type_id, $portfolio_id, $portfolio_name);
        return$this->model->selectRaw('sum(balance) as opening, gl_codes.id, gl_codes.code, gl_codes.description, gl_codes.account_type, p.name AS portfolio_name, coalesce(p.shortcode,?) as shortcode', [$portfolio_name])
        ->leftJoin('opening_balances as ob', 'ob.gl_code_id', '=', 'gl_codes.id')
        ->leftJoin('opening_balance_mappings as obm', 'obm.opening_balance_id', '=', 'ob.id')
        ->leftJoin('portfolios as p', 'obm.portfolio_id', '=', 'p.id')
        ->whereIn('gl_codes.id', $glcodes)
        ->when($accounting_year , function($query) use ($accounting_year) {
            $query->where('ob.accounting_year_id',$accounting_year);
        })
        ->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('ob.business_type_id', $business_type_id);
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('ob.branch_id', $branch_id);
        })
        ->when($portfolio_id, function ($q) use ($portfolio_id, $headOffice_id) {
            $q->when($portfolio_id != 'All', function ($q) use ($portfolio_id, $headOffice_id) {
                $q->when($portfolio_id == $headOffice_id, function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('obm.portfolio_id', $portfolio_id)->orWhereNull('obm.portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('obm.portfolio_id', $portfolio_id);

                });
            });
        })
        ->groupBy('gl_codes.id', 'gl_codes.code', 'gl_codes.description', 'gl_codes.account_type', 'shortcode', 'portfolio_name')->get();
    }

    public function fetchLedgerOpeningSum($glcode_id, $accounting_year, $business_type_id = "All", $portfolio_id = 'All')
    {
        return $this->model->with(['openingBalances', function($query) use($portfolio_id, $accounting_year, $business_type_id) {
            $query->when($portfolio_id != 'All', function($query) use ($portfolio_id) {
                $query->with(['openingBalanceMappings', function($query) use($portfolio_id) {
                    $query->with(['portfolio', function($query) use($portfolio_id) {
                        $query->where('id', $portfolio_id);
                    }]);
                }]);
            })
            ->when($business_type_id != 'All', function($query) use ($business_type_id) {
                    $query->where('business_type_id', $business_type_id);
                })
            ->where('accounting_year_id', $accounting_year);
        }])
        ->where('gl_codes.id', $glcode_id)->first();
    }

    // Journal Entries Sum with credit and debit groupby portfolios for disclosure excel
    public function fetchGLCodeWithCreditDebitPortfoliosForDisclosure($glcodes, $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = null, $headOffice_id, $portfolio_name)
    {
        return $this->model->selectRaw('
        SUM(CASE WHEN je.transaction_amount < 0 THEN je.transaction_amount ELSE 0 END) AS credit,
        SUM(CASE WHEN je.transaction_amount >= 0 THEN je.transaction_amount ELSE 0 END) AS debit,
        gl_codes.id,
        gl_codes.code,
        gl_codes.description,
        COALESCE(p.shortcode, ?) AS shortcode,
        COALESCE(p.type, ?) AS Portfolio_type,
        COALESCE(p.name, ?) AS portfolio_name',
            [$portfolio_name, null, null]
        )
            ->leftJoin('journal_entries as je', 'je.gl_code_id', '=', 'gl_codes.id')
            ->leftJoin('journal_mappings as jm', 'jm.journal_entries_id', '=', 'je.id')
            ->leftJoin('portfolios as p', 'jm.portfolio_id', '=', 'p.id')
            ->whereIn('gl_codes.id', $glcodes)
            ->whereBetween('je.system_date', [$start_date, $end_date])
            ->when($accounting_year, function ($query) use ($accounting_year) {
                $query->where('je.accounting_year_id', $accounting_year);
            })
            ->when($business_type_id != 'All', function ($query) use ($business_type_id) {
                $query->where('je.business_type_id', $business_type_id);
            })
            ->when($branch_id != 'All', function ($query) use ($branch_id) {
                $query->where('je.branch_id', $branch_id);
            })
            ->when($portfolio_id, function ($q) use ($portfolio_id, $headOffice_id) {
                $q->when($portfolio_id != 'All', function ($q) use ($portfolio_id, $headOffice_id) {
                    $q->when($portfolio_id == $headOffice_id, function ($q) use ($portfolio_id) {
                        $q->where(function ($query) use ($portfolio_id) {
                            $query->where('jm.portfolio_id', $portfolio_id)->orWhereNull('jm.portfolio_id');
                        });
                    }, function ($q) use ($portfolio_id) {
                        $q->where('jm.portfolio_id', $portfolio_id);
                    });
                });
            })->groupBy(
                'gl_codes.id',
                'gl_codes.code',
                'gl_codes.description',
                'shortcode',
                'p.type',
                'p.name'
            )->get();
    }

    public function fetchGlCodeWithPortfoliosOpeningForDisclosure($glcodes, $accounting_year, $headOffice_id, $business_type_id = "All", $portfolio_id = 'All', $portfolio_name)
    {
        return $this->model->selectRaw(
            'sum(balance) as opening,
         gl_codes.id,
         gl_codes.code,
         gl_codes.description,
         coalesce(p.shortcode, ?) as shortcode,
         coalesce(p.type, ?) as Portfolio_type,
         coalesce(p.name, ?) as portfolio_name',
            [$portfolio_name, null, null]
        )
            ->leftJoin('opening_balances as ob', 'ob.gl_code_id', '=', 'gl_codes.id')
            ->leftJoin('opening_balance_mappings as obm', 'obm.opening_balance_id', '=', 'ob.id')
            ->leftJoin('portfolios as p', 'obm.portfolio_id', '=', 'p.id')
            ->whereIn('gl_codes.id', $glcodes)
            ->when($accounting_year, function ($query) use ($accounting_year) {
                $query->where('ob.accounting_year_id', $accounting_year);
            })
            ->when($business_type_id != 'All', function ($query) use ($business_type_id) {
                $query->where('ob.business_type_id', $business_type_id);
            })
            ->when($portfolio_id, function ($q) use ($portfolio_id, $headOffice_id) {
                $q->when($portfolio_id != 'All', function ($q) use ($portfolio_id, $headOffice_id) {
                    $q->when($portfolio_id == $headOffice_id, function ($q) use ($portfolio_id) {
                        $q->where(function ($query) use ($portfolio_id) {
                            $query->where('obm.portfolio_id', $portfolio_id)->orWhereNull('obm.portfolio_id');
                        });
                    }, function ($q) use ($portfolio_id) {
                        $q->where('obm.portfolio_id', $portfolio_id);
                    });
                });
            })
            ->groupBy(
                'gl_codes.id',
                'gl_codes.code',
                'gl_codes.description',
                'shortcode',
                'p.type',
                'p.name'
            )->get();
    }

    public function fetchGlCodesDetails(array $ids, $provision_setting_id)
    {

        return $this->model->select(['id','code', 'description'])->with(['expenseAllocation' => function($q) use ($provision_setting_id) {
            return $q->where('provision_setting_id', CustomHelper::decode($provision_setting_id));
        }])->whereIn('id', $ids)->get();
    }
}
