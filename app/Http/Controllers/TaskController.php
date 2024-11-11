<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\MarkCompleteTaskRequest;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tasks = $request->user()->tasks()->latest()->get();
            return response()->json($tasks);
        } catch (\Exception $e) {
            Log::error('Failed to fetch tasks: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve tasks'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreTaskRequest $request)
    {
        try {
            $task = $request->user()->tasks()->create($request->validated());
            return response()->json(['message' => 'Task created successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to create task: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create task'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function markComplete(MarkCompleteTaskRequest $request, Task $task)
    {
        try {
            $this->authorize('markComplete', $task);
            $task->update(['is_completed' => true]);
            return response()->json(['message' => 'Task marked as complete successfully']);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized to mark this task as complete'], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            Log::error('Failed to mark task as complete: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to mark task as complete'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request, Task $task)
    {
        try {
            $this->authorize('delete', $task);
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized to delete this task'], Response::HTTP_FORBIDDEN);
        }catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record Not Found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Failed to delete task: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete task'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
