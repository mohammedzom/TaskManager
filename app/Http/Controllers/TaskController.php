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
        $validatedData = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'priority' => 'required|integer|min:1|max:5'
        ]);
        $task = Task::create($validatedData);
        return response()->json($task, 201);
    }
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:50',
            'description' => 'sometimes|required|string',
            'priority' => 'sometimes|required|integer|min:1|max:5'
        ]);
        $task->update($validatedData);
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