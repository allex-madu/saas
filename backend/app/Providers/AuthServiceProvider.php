<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Person;
use App\Models\User;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PersonPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,  
        User::class => UserPolicy::class,
        Person::class => PersonPolicy::class,
        City::class => CityPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
