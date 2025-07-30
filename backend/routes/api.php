<?php

use App\Http\Controllers\Admin\BakeryController as AdminBakeryController;
use App\Http\Controllers\Api\Admin\CityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\PermissionTreeController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\PersonController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserRoleController;
use App\Http\Controllers\Api\Admin\BakeryController;


Route::prefix('v1')->group(function () {

    // Rota pública de login (sem autenticação)
    Route::post('/login', [AuthController::class, 'login']);

    // Rotas que requerem autenticação do usuário
    Route::middleware(['auth:sanctum', 'checkBackeryAccess'])->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Rotas administrativas (autenticado e com papel admin/super-admin)
    //Route::middleware(['auth:sanctum', 'role:admin|super-admin'])->prefix('admin')->group(function () {
    Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {

        Route::apiResource('bakeries', BakeryController::class);

        // CRUD de usuários
        Route::apiResource('users', UserController::class);
        Route::get('users/{user}/edit', [UserController::class, 'edit']);

        // CRUD de papéis (roles)
        Route::apiResource('role-management', RoleController::class)
              ->parameters(['role-management' => 'role']);

        // Permissões como árvore hierárquica
        Route::get('/permissions/tree', [PermissionTreeController::class, 'index']);

        // Permissões agrupadas por módulo (ex: users, products, etc.)
        Route::get('/permissions/grouped', [PermissionController::class, 'grouped']);

        // CRUD de permissões
        Route::apiResource('permissions', PermissionController::class);

        // Sincronização de papéis de um usuário
        Route::post('/users/{user}/roles', [UserRoleController::class, 'syncRoles']);

        // Listagem de pessoas vinculadas
        Route::get('/people', [PersonController::class, 'index']);

        Route::get('/roles', [RoleController::class, 'listAll']);

        Route::get('/cities', [CityController::class, 'index']);


    });

});

/*
|--------------------------------------------------------------------------
| Exemplos adicionais para referência
|--------------------------------------------------------------------------
|
| Rota protegida por papel:
|
| Route::middleware(['auth:sanctum', 'role:gerente'])->get('/gerente/dashboard', function () {
|     return response()->json(['message' => 'Acesso liberado para gerente!']);
| });
|
| Rota protegida por permissão:
|
| Route::middleware(['auth:sanctum', 'permission:update_products'])->post('/produtos', function () {
|     return response()->json(['message' => 'Permissão para editar produtos!']);
| });
|
*/
