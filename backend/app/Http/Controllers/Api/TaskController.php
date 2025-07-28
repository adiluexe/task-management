<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = $this->taskService->getAllTasks(
            $request->user(),
            $request->all()
        );

        return response()->json([
            'success' => true,
            'data' => TaskResource::collection($tasks->items()),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'from' => $tasks->firstItem(),
                'to' => $tasks->lastItem(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'message' => 'Task created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $task = $this->taskService->updateTask($task, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'message' => 'Task updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

    /**
     * Toggle task status (pending/completed).
     */
    public function toggleStatus(Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $task = $this->taskService->toggleTaskStatus($task);

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'message' => 'Task status updated successfully'
        ]);
    }

    /**
     * Reorder tasks.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.position' => 'required|integer|min:1'
        ]);

        $this->taskService->reorderTasks(
            $request->user(),
            $request->input('tasks')
        );

        return response()->json([
            'success' => true,
            'message' => 'Tasks reordered successfully'
        ]);
    }
}
