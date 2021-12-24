<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;




class TaskController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function store(Request $request)
    {
       $validator= Validator::make($request->all(),['name'=>'required|max:255']);
       if($validator->fails())
       {
           return response()->json([
               'status'=>400,
               'errors'=>$validator->messages(),
           ]);
       }
       else
       {
        $task= new Task;
        $task->name = $request->input('name');
        $task->status = 0;
        $task->save();
        return response()->json([
            'status'=>200,
            'message'=>'Task Added Successfully',
            ]);
        }
    }
    public function tasklist()
    {
        $task=Task::where('status',0)->get();
        return response()->json([
            'task'=>$task,
        ]);
    }
    public function alltasklist()
    {
        $task=Task::all();
        return response()->json([
            'task'=>$task,
        ]);
    }
    public function editTask($id)
    {
        $task= Task::find($id);
        if($task)
        {
            return response()->json([
                'status'=>200,
                'task'=>$task,
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'task not found',
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $validator= Validator::make($request->all(),['name'=>'required|max:255']);
        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }
        else
        {
         $task= Task::find($id);
         if($task)
        {
         $task->name = $request->input('name');
        //  $task->status = 0;
         $task->update();
         return response()->json([
             'status'=>200,
             'message'=>'Task Updated Successfully',
             ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'task not found',
            ]);
        }

         }
    }
    public function destroy($id)
    {
        $task= Task::find($id);
        $task->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Task Deleted Successfully',
            ]);

    }

}
