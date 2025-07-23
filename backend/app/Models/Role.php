<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $guard_name = 'web'; 

    public $timestamps = true;

    protected $guarded = [];
    
    protected $fillable = [
        'name',
        'description',
        'guard_name',
    ];
}
