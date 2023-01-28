<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserListResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        if (!Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            return response(['message' => 'invalid login credentials.']);
        }
        $accessToken = Auth::user()->createToken('authToken')->plainTextToken;
//
//        $temp = Auth::user();
//        $user = [
//            'id' => $temp->id,
//            'name' => $temp['name_' . $locale],
//            'role_name' => $temp->role->name,
//            'role' => $temp->role['name_' . $locale],
//            'permissions'=> $temp->role->permissions,
//            'bio' => $temp['bio_' . $locale],
//            'image' => $temp->image
//        ];
        return response(['user' => new UserResource(Auth::user()), 'access_token' => $accessToken]);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|min:2|max:250|unique:users',
            'password' => 'required|max:64|min:8',
            'password_confirm' => 'required|same:password',
            'name' => 'required|min:2|max:250',
            'photo' => 'image|size:8192|mimes:jpeg,bmp,png,jpg',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'validation fails',
                'errors' => $validate->errors()
            ], 400);
        }
        $user = new User();
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->name = $request->input('name');
        $image = $request->file('photo') ?? null;
        if ($image) {
            $filename = time() .'.'.$image->getClientOriginalExtension();
            $tmp = $image->storeAs('users', $filename, 'public');
            $user->photo = $tmp;
        }
        $user->role_id = 1;
        $user->save();

        return response()->json([
            'message' => 'register is successful'
        ], 200);
    }

    public function current_user(Request $request)
    {
        $user = User::find($request->user()->id);
        return new UserResource(Auth::user());
    }
    public function all_user_list(Request $request){
        return new UserListResource(User::paginate(3));
    }
}

