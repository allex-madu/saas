<?php

namespace App\Scopes;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;

class BackeryScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::user();

        if ($user instanceof User && !$user->isSuperAdmin()) {
            $builder->where($model->getTable() . '.bakery_id', $user->bakery_id);
        }
    }
}
