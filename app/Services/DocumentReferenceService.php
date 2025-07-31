<?php

namespace App\Services;

use App\Models\DocumentReference;

class DocumentReferenceService
{

    protected $model;

    public function __construct()
    {
        $this->model = new DocumentReference();
    }

    public function create($document_reference)
    {
        $model = $this->model->create([
            "reference" => $document_reference
        ]);
        return $model;
    }

    public function fetchOrCreate($document_reference)
    {
        $id =$this->getId($document_reference);
        if($id)
            return $id;
        else
            return $this->create($document_reference)->id;
    }

    public function getId($reference)
    {
        return $this->model->where('reference', $reference)->first()->id ?? null;
    }


}
