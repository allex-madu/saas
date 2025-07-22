<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    
    public $timestamps = true;

    protected $guarded = [];
    
    protected $fillable = [
        'id',
        'name',
        'guard_name',
    ];
}
