<?php

namespace App\Scopes;

use App\Helpers\CustomHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class OrganizationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $authUser = Auth::user();
        if($authUser){
            if(!$authUser->hasRole('admin'))
                $builder->where($model->getTable().'.organization_id', $authUser->organization->id);
            else if(request()->query('org'))
                $builder->where('organization_id', CustomHelper::decode(request()->query('org')));
        }
    }
}
