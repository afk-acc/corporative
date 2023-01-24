<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{



    public function add(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|min:2|max:250|unique:roles',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        if(!$request->user()->hasAccess('role.create')){
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }
        $role = new Role();
        $role->name = $request->input('name');
        $role->save();
        return response()->json(['message' => 'role created is successfull'], 200);
    }

    public function update_role(Request $request){
        $validate = Validator::make($request->all(), [
            'id'=>'required|numeric',
            'name' => 'required|min:2|max:250|unique:roles',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        if(!$request->user()->hasAccess('role.update')){
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }
        $role = Role::find($request->input('id'));
        $role->name= $request->input('name');
        $role->save();
        return response()->json(['message' => 'role update is successfull'], 200);
    }
    public function delete(Request  $request){
        $validate = Validator::make($request->all(), [
            'id'=>'required|numeric',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        if(!$request->user()->hasAccess('role.delete')){
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }
        Role::destroy($request->input('id'));
        return response()->json(['message' => 'role delete is successfull'], 200);
    }
    public function index(Request $request)
    {
        if(!$request->user()->hasAccess('role.read')){
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }
        $roles = Role::all();
        return response()->json(['data' => $roles], 200);
    }

    public function change_permission(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'role_id'=>'required|numeric',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        /*
         * role_id = integer
         * permissions = [
         * 'value':integer 1 or 0
         * 'id':permission_id
         * ]
         */
        if (!Auth::user()->hasAccess('role.update'))
            return response([
                'message' => 'not permission'
            ], 403);
        $role = Role::find($request->input('role_id'));
        $permissions = $request->input('permissions');

        foreach ($permissions as $permission) {
            $isContain = false;
            foreach ($role->permissions as $item) {
                if ($item->id == $permission['id']) {
                    $isContain = true;
                    if ($permission['value'] == 0) {
                        RolePermission::where('role_id', '=', $role->id)->where('permission_id', '=', $permission['id'])->delete();
                    }
                }
            }
            if(!$isContain && $permission['value'] == 1){
                $temp = new RolePermission;
                $temp->role_id = $role->id;
                $temp->permission_id = $permission['id'];
                $temp->save();
            }
        }
        return response([
            'message'=>'successfuly'
        ],201);
    }

    public function get_permission_by_role(Request $request){
        $res = Permission::all();
        $role = Role::find($request->input('id'));
        foreach ($res as $item){
            $isContain = false;
            foreach ($role->permissions as $permission){
                if($permission->id == $item->id)
                {
                    $isContain = true;
                    break;
                }
            }
            if($isContain)
                $item->value = 1;
            else $item->value = 0;
        }
        return $res;
    }
}
