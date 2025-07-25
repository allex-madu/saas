<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionTreeController extends Controller
{
    public function index()
    {
        // Garante que o usuário tem permissão para visualizar a estrutura de permissões
        //$this->authorize('viewAny', Permission::class);

        return response()->json(Permission::treeStructure());
    }
}
