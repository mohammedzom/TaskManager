<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks;

        return TaskResource::collection($tasks);
    }

    public function getAllTasks()
    {
        $tasks = Task::all();

        return TaskResource::collection($tasks, 200);

    }

    public function getTaskOrderByPriority()
    {
        $tasks = Auth::user()->tasks()->orderByRaw('FIELD(priority, "high", "medium", "low")')->get();
        if ($tasks->isEmpty()) {
            $exception = new ModelNotFoundException('Task', 404);
            $exception->setModel(Task::class);

            throw $exception;
        }

        return TaskResource::collection($tasks);
    }

    public function getTaskOrderByPriorityByUser($sort_direction)
    {
        if (strtoupper($sort_direction) === 'ASC') {
            $tasks = Auth::user()->tasks()->orderByRaw('FIELD(priority, "low", "medium", "high")')->get();
        } elseif (strtoupper($sort_direction) === 'DESC') {
            $tasks = Auth::user()->tasks()->orderByRaw('FIELD(priority, "high", "medium", "low")')->get();
        } else {
            return response()->json(['message' => 'Invalid Sort Direction'], 422);
        }

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Auth::user()->tasks()->create($request->validated());

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, $id)
    {

        $task = Auth::user()->tasks()->findOrFail($id);
        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function show($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);

        return new TaskResource($task);
    }

    public function destroy($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }

    public function getTaskUser($id)
    {
        $user = Auth::user()->tasks()->findOrFail($id)->user;

        return new UserResource($user);
    }

    public function addCategoriesToTasks(Request $request, $taskId)
    {
        if ($request->has('category_id') && ! is_array($request->category_id)) {
            $request->merge(['category_id' => [$request->category_id]]);
        }
        $request->validate([
            'category_id' => 'required|array',
            'category_id.*' => 'integer|exists:categories,id',
        ]);
        $task = Auth::user()->tasks()->findOrFail($taskId);

        $task->categories()->syncWithoutDetaching($request->category_id);

        return response()->json('Category attached successfully', 200);
    }

    public function getCategoriesTasks($categoryId)
    {
        $tasks = Auth::user()->tasks()
            ->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })
            ->get();

        if ($tasks->isEmpty()) {
            $exception = new ModelNotFoundException;
            $exception->setModel(Task::class);
            throw $exception;
        }

        return TaskResource::collection($tasks);

    }

    public function getTasksCategories($taskId)
    {
        $categories = Auth::user()->tasks()->findOrFail($taskId)->categories;

        return CategoryResource::collection($categories, 200);

    }

    public function addToFavorites($taskId)
    {
        $task = Auth::user()->tasks()->findOrFail($taskId);
        Auth::user()->favoriteTasks()->syncWithoutDetaching($taskId);

        return response()->json(['message' => 'Task added to favorites'], 200);
    }

    public function removeFromFavorites($taskId)
    {
        Auth::user()->tasks()->findOrFail($taskId);
        Auth::user()->favoriteTasks()->detach($taskId);

        return response()->json(['message' => 'Task removed from favorites'], 200);
    }

    public function getFavoriteTasks()
    {
        $favorites = Auth::user()->favoriteTasks;

        return TaskResource::collection($favorites, 200);
    }
}
