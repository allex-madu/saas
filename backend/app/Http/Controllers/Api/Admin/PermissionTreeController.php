<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionTreeController extends Controller
{
    public function index()
    {
        return response()->json(Permission::treeStructure());
    }

}
