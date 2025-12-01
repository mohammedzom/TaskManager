<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        // $task = Task::all();
        $tasks = Auth::user()->tasks;

        return response()->json($tasks, 200);
    }

    public function store(StoreTaskRequest $request)
    {
        // $user_id = Auth::user()->id;
        // $ValidatedData = $request->validated();
        // $ValidatedData['user_id'] = $user_id;
        // $task = Task::create($ValidatedData);
        $task = Auth::user()->tasks()->create($request->validated());

        return response()->json($task, 201);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $user_id = Auth::user()->id;
        $task = Task::findOrFail($id);
        if ($task->user_id != $user_id) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        $task->update($request->validated());

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

    public function getTaskUser($id)
    {
        $user = Task::findOrFail($id)->user;

        return response()->json($user, 200);
    }

    public function addCategoriesToTasks(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->categories()->attach($request->category_id);

        return response()->json('Category attached successfuly', 200);
    }

    public function getCategoriesTasks($category_id)
    {
        $tasks = Category::findOrFail($category_id)->tasks;

        return response()->json($tasks, 200);

    }

    public function getTasksCategories($taskId)
    {
        $categories = Task::findOrFail($taskId)->categories; // fix find

        return response()->json($categories, 200);

    }
}
