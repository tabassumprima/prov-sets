<?php

namespace App\Services;

use App\Models\TransactionType;

class TransactionTypeService
{

    protected $model;

    public function __construct()
    {
        $this->model = new TransactionType();
    }

    public function getId($type)
    {
        return (optional($this->model->where('type', $type)->first())->id)??1;
    }


}
