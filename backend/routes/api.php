<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserRoleController;


Route::prefix('v1')->group(function () {
    
    // Rota de login (não precisa de autenticação)
    Route::post('/login', [AuthController::class, 'login']);
    
    // Rotas que precisam do usuário autenticado
    Route::middleware('auth:sanctum', 'checkBackeryAccess')->group(function () 
    {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
    });

   
    Route::middleware(['auth:sanctum', 'role:super-admin'])->prefix('admin')->group(function () {
        //Route::get('/users', [UserRoleController::class, 'index']);
        Route::apiResource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::post('/users/{user}/roles', [UserRoleController::class, 'syncRoles']);
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
