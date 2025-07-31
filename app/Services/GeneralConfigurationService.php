<?php

namespace App\Services;

use App\Models\GeneralConfiguration;

class GeneralConfigurationService
{
    public function fetch($identifier, $organization_id = null)
    {
        $settings =  GeneralConfiguration::where('identifier', $identifier)
            ->when($organization_id, function ($q) use ($organization_id) {
                $q->where('organization_id', $organization_id);
            })->get();
            dd($settings);
        return $settings;
    }

    public function create($data)
    {
        extract($data);
        $model = new GeneralConfiguration();
        $model->identifier        = $identifier;
        $model->data              = isset($jsonData) ? json_encode($jsonData) : null;
        $model->organization_id   = $organization_id ?? null;
        $model->file_url          = $file_url ?? null;
        $model->status            = true;
        $model->save();
        return $model;
    }
}
