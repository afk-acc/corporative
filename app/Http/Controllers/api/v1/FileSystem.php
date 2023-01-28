<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileSystem extends Controller
{
    //


    public function get_folders(Request $request)
    {
        if (!$request->user()->hasAccess('folder.read')) {
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }

        $validate = Validator::make($request->all(), [
            'parent' => 'required|numeric',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        $folders = Folder::where('parent', '=', $request->input('parent'))->get();
        $files = File::where('folder_id', '=', $request->input('parent'))->get();
        return \response()->json([
            'folders'=>$folders,
            'files'=>$files
        ],200);
    }

    public function create_folder(Request $request)
    {
        if (!$request->user()->hasAccess('folder.create')) {
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }
        $validate = Validator::make($request->all(), [

            'name' => 'required|min:2',
            'parent'=>'required|numeric'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        $folder = new Folder;
        $folder->name = $request->input('name');
        $folder->parent = $request->input('parent');
        $folder->save();
        return \response()->json([
            'message' => 'folder create success'
        ], 201);
    }

    public function update_folder(Request $request)
    {
        if (!$request->user()->hasAccess('folder.update')) {
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }
        $validate = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'name' => 'required|min:2'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        $folder = Folder::find($request->input('id'));
        $folder->name = $request->input('name');
        $folder->save();
        return \response()->json([
            'message' => 'folder update is success'
        ], 200);
    }

    public function delete_folder(Request $request)
    {
        if (!$request->user()->hasAccess('folder.delete')) {
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }
        $validate = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        $folder = Folder::destroy($request->input('id'));
        return \response()->json([
            'message' => 'folder destroy is success'
        ], 200);
    }

    public function add_file(Request $request)
    {
//        if (!$request->user()->hasAccess('file.create')) {
//            return response()->json([
//                'message' => 'operation not allowed',
//            ], 403);
//        }
        $validate = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpeg,jpg,pdf,doc,docx,xls,xlsx,png,txt',
            'folder_id' => 'required|numeric'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        $file = $request->file('file') ?? null;
        $f = new File;
        $filename = $file->getClientOriginalName().'_'.time() . '.' . $file->getClientOriginalExtension();
        $folder = Folder::find($request->input('folder_id'));
        $tmp = $file->storeAs('folders/'.$folder->name, $filename, 'public');
        $f->file = $tmp;
        $f->name = $file->getClientOriginalName();
        $f->folder_id = $request->input('folder_id');
        $f->save();
        return \response()->json([
            'message' => 'file upload is successfull'
        ], 200);
    }

    public function remove_file(Request $request)
    {
        if (!$request->user()->hasAccess('file.delete')) {
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }
        $validate = Validator::make($request->all(), [
            'id' => 'required|numeric'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        File::destroy($request->input('id'));
        return \response()->json([
            'message' => 'file delete is successfull'
        ], 200);
    }
    public function get_files(Request $request){
        if (!$request->user()->hasAccess('file.read')) {
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }
        $validate = Validator::make($request->all(), [
            'id' => 'required|numeric'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        return File::where('folder_id', '=',$request->input('id'))->get();
    }

}
