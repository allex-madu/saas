<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionTreeController extends Controller
{
    public function index()
    {
        return response()->json(Permission::PERMISSIONS);
    }

    public function tree()
    {
        return response()->json(Permission::PERMISSIONS);
    }
}
