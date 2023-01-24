<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{

    public function index(Request $request)
    {
        if (Auth::user()->hasAccess('permission.read'))
            return Permission::all();
        else return response([
            'message' => 'not permission'
        ], 403);
    }


}
