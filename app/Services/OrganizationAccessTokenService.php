<?php

namespace App\Services;

use App\Models\OrganizationAccessToken;

class OrganizationAccessTokenService
{
    protected $model;
    public function __construct()
    {
        $this->model = new OrganizationAccessToken();
    }

    public function createDefaultAccessToken($id)
    {
        return $this->model->create([
            'title'           => 'Default Token',
            'organization_id' => $id,
            'secret_key'      => $this->generateSecretKey(),
            'expires_at'      => now()->addDays(90)->endOfDay(),
        ]);
    }

    public function organizationKeys($id, $relations = array())
    {
        return $this->model->with($relations)->where('organization_id', $id)->latest()->first();
    }

    static private function generateSecretKey()
    {
        return 'token_' . bin2hex(random_bytes('20'));
    }

    public function delete($organization_id)
    {
        return $this->model->where('organization_id', $organization_id)->delete();
    }

    public function createAccessToken($id)
    {
        return $this->model->updateOrCreate(['organization_id' => $id], [
            'organization_id' => $id,
            'secret_key'      => $this->generateSecretKey(),
            'expires_at'      => now()->addDays(90)->endOfDay(),
        ]);
    }
}
