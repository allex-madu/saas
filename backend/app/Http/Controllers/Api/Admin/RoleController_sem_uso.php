<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Busca todos os papéis
        $roles = Role::all(); // Se estiver usando Spatie

        // Retorna os papéis em formato JSON
        return response()->json([
            'roles' => $roles->map(function($role) {
                return [
                    'value' => $role->id,
                    'label' => $role->name,
                ];
            })
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
