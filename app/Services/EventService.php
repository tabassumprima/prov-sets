<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Event();
    }

    public function fetchAll()
    {
       return $this->model->all();
    }

    public function getUniqueEventType()
    {
        return $this->fetchAll()->unique('type');    
    }

}
