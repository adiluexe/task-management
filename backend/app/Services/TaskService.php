<?php

namespace App\Services;

use App\Models\User;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Get tasks for a user with filtering and search.
     */
    public function getTasksForUser(
        User $user,
        int $perPage = 15,
        ?string $status = null,
        ?string $priority = null,
        ?string $search = null
    ): LengthAwarePaginator {
        return $this->taskRepository->getTasksForUser($user, $perPage, $status, $priority, $search);
    }

    /**
     * Get all tasks for a user (alias for getTasksForUser).
     */
    public function getAllTasks(User $user, array $filters = []): LengthAwarePaginator
    {
        return $this->getTasksForUser(
            $user,
            $filters['per_page'] ?? 15,
            $filters['status'] ?? null,
            $filters['priority'] ?? null,
            $filters['search'] ?? null
        );
    }

    /**
     * Create a new task.
     */
    public function createTask(User $user, array $data): Task
    {
        // Set default values
        $data['status'] = $data['status'] ?? 'pending';
        $data['priority'] = $data['priority'] ?? 'medium';

        return $this->taskRepository->createTask($user, $data);
    }

    /**
     * Update a task.
     */
    public function updateTask(Task $task, array $data): Task
    {
        // Ensure the user can only update their own tasks
        $currentUser = auth()->user();
        if ($task->user_id !== $currentUser->id && !$currentUser->isAdmin()) {
            throw new \Exception('Unauthorized to update this task.');
        }

        return $this->taskRepository->updateTask($task, $data);
    }

    /**
     * Delete a task.
     */
    public function deleteTask(Task $task): bool
    {
        // Ensure the user can only delete their own tasks or is admin
        $currentUser = auth()->user();
        if ($task->user_id !== $currentUser->id && !$currentUser->isAdmin()) {
            throw new \Exception('Unauthorized to delete this task.');
        }

        return $this->taskRepository->deleteTask($task);
    }

    /**
     * Reorder tasks for a user.
     */
    public function reorderTasks(User $user, array $tasks): bool
    {
        // Validate that all tasks belong to the user
        $taskIds = collect($tasks)->pluck('id');
        $userTaskIds = Task::where('user_id', $user->id)
            ->whereIn('id', $taskIds)
            ->pluck('id');

        if ($taskIds->count() !== $userTaskIds->count()) {
            throw new \Exception('Some tasks do not belong to this user.');
        }

        return $this->taskRepository->reorderTasks($user, $tasks);
    }

    /**
     * Toggle task status.
     */
    public function toggleTaskStatus(Task $task): Task
    {
        $newStatus = $task->status === 'pending' ? 'completed' : 'pending';
        return $this->updateTask($task, ['status' => $newStatus]);
    }

    /**
     * Get all tasks with users (admin only).
     */
    public function getAllTasksWithUsers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->taskRepository->getAllTasksWithUsers($perPage);
    }

    /**
     * Clean up old tasks.
     */
    public function cleanupOldTasks(int $days = 30): int
    {
        return $this->taskRepository->deleteOldTasks($days);
    }

    /**
     * Get recent tasks for admin dashboard.
     */
    public function getRecentTasks(int $limit = 10): Collection
    {
        return $this->taskRepository->getRecent($limit);
    }
}
