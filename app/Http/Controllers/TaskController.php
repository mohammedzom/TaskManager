<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $task = Task::all();

        return response()->json($task, 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());

        return response()->json($task, 201);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
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
