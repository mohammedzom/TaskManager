<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $task = Task::all();
        return response()->json($task, 200);
    }
    public function store(Request $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority
        ]);
        return response()->json($task, 201);
        // $task = new Task;
        // $task->title = $request->title;
        // $task->description = $request->description;
        // $task->priority = $request->priority;
        // $task->save();
        // return response()->json($task, 201);
    }
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->only(['title', 'description', 'priority']));
        // $task = Task::findOrFail($id);
        // if ($request->title != null)
        //     $task->title = $request->title;
        // if ($request->description != null)
        //     $task->description = $request->description;
        // if ($request->priority != null)
        //     $task->priority = $request->priority;
        // $task->save();
        return response()->json($task, 200);
    }
    public function show(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task, 200);
    }
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}