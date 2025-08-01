<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToBakery
{
    protected static function bootBelongsToBakery()
    {
        static::addGlobalScope('bakery', function (Builder $builder) {
            if (session()->has('active_bakery_id')) {
                $builder->where('bakery_id', session('active_bakery_id'));
            }
        });
    }

    public function bakery()
    {
        return $this->belongsTo(\App\Models\Bakery::class);
    }
}
