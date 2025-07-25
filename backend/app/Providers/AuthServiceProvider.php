<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Permission;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Role::class => RolePolicy::class,
        
        //Permission::class => PermissionPolicy::class, 
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
