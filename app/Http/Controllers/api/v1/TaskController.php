<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResourse;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    //
    public function from_me(Request $request)
    {
        return new TaskResourse(Task::where('from_user','=',$request->user()->id)->get());
    }

    public function to_me(Request $request)
    {
        return new TaskResourse(Task::where('to_user','=',$request->user()->id)->get());
    }


    public function add_task(Request $request)
    {

        if (!$request->user()->hasAccess('task.create')) {
            return response()->json([
                'message' => 'operation not allowed',
            ], 403);
        }
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xls,xlsx,jpg,png,jpeg',
            'deadline' => 'required|date|after:tomorrow',
            'to_user' => 'required|numeric'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validate->errors()
            ], 400);
        }
        $task = new Task;
        $task->from_user = $request->user()->id ;
        $task->to_user = $request->input('to_user');
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->deadline = $request->input('deadline');
        $file = $request->file('file') ?? null;
        if ($file) {
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $tmp = $file->storeAs('tasks', $filename, 'public');
            $task->file = $tmp;
        }
        $task->save();
        return response()->json([
            'message' => 'task created'
        ], 201);
    }




}
