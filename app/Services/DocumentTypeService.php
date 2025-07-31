<?php 

namespace App\Services;

use App\Models\DocumentType;

class DocumentTypeService
{

    protected $model;

    public function __construct()
    {
        $this->model = new DocumentType();
    }

    public function getId($type)
    {
        return $this->model->where('type', $type)->first()->id;
    }


}