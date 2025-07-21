<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\PermissionTreeController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\PersonController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserRoleController;

Route::prefix('v1')->group(function () {
    
    // Rota de login (não precisa de autenticação)
    Route::post('/login', [AuthController::class, 'login']);
    
    // Rotas que precisam do usuário autenticado
    Route::middleware('auth:sanctum', 'checkBackeryAccess')->group(function () 
    {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
    });

   
    Route::middleware(['auth:sanctum', 'role:admin|super-admin'])->prefix('admin')->group(function () {

        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::get('/permissions/tree', [PermissionTreeController::class, 'index']);
        Route::get('/permissions/grouped', [PermissionController::class, 'grouped']);
        Route::apiResource('permissions', PermissionController::class);
        Route::post('/users/{user}/roles', [UserRoleController::class, 'syncRoles']);
        Route::get('/people', [PersonController::class, 'index']);
        Route::get('roles', [UserRoleController::class, 'index']);

        Route::get('permissions/grouped', function () {
            $permissions = \Spatie\Permission\Models\Permission::all();

            $grouped = $permissions->groupBy(function ($permission) {
                return explode('.', $permission->name)[0]; // ex: "users" de "users.view"
                });

                return response()->json($grouped);
            });
        });
    
    


    
});

/*


Exemplo de rota protegida por role

    Route::middleware(['auth:sanctum', 'role:gerente'])->get('/gerente/dashboard', function () {
        return response()->json(['message' => 'Acesso liberado para gerente!']);
    });

Exemplo de rota protegida por permissão

    Route::middleware(['auth:sanctum', 'permission:	update_products'])->post('/produtos', function () {
        return response()->json(['message' => 'Permissão para editar produtos!']);
    });

    
*/
