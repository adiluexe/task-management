<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TaskRepository
{
    protected $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    /**
     * Get paginated tasks for a user with filtering and search.
     */
    public function getTasksForUser(
        User $user,
        int $perPage = 15,
        ?string $status = null,
        ?string $priority = null,
        ?string $search = null
    ): LengthAwarePaginator {
        $cacheKey = "user_tasks_{$user->id}_{$perPage}_{$status}_{$priority}_{$search}_" . md5(serialize(func_get_args()));

        return Cache::remember($cacheKey, 300, function () use ($user, $perPage, $status, $priority, $search) {
            $query = $this->model
                ->where('user_id', $user->id)
                ->ordered();

            if ($status) {
                $query->byStatus($status);
            }

            if ($priority) {
                $query->byPriority($priority);
            }

            if ($search) {
                $query->search($search);
            }

            return $query->paginate($perPage);
        });
    }

    /**
     * Create a new task for a user.
     */
    public function createTask(User $user, array $data): Task
    {
        $data['user_id'] = $user->id;
        $data['order'] = $data['order'] ?? Task::getNextOrder($user->id);

        $task = $this->model->create($data);

        // Clear cache for this user
        $this->clearUserTasksCache($user->id);

        return $task;
    }

    /**
     * Update a task.
     */
    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);

        // Clear cache for this user
        $this->clearUserTasksCache($task->user_id);

        return $task->fresh();
    }

    /**
     * Delete a task.
     */
    public function deleteTask(Task $task): bool
    {
        $userId = $task->user_id;
        $result = $task->delete();

        // Clear cache for this user
        $this->clearUserTasksCache($userId);

        return $result;
    }

    /**
     * Reorder tasks for a user.
     */
    public function reorderTasks(User $user, array $tasks): bool
    {
        $success = true;

        foreach ($tasks as $taskData) {
            $task = $this->model->where('id', $taskData['id'])
                ->where('user_id', $user->id)
                ->first();

            if ($task) {
                $task->update(['order' => $taskData['order']]);
            } else {
                $success = false;
            }
        }

        // Clear cache for this user
        $this->clearUserTasksCache($user->id);

        return $success;
    }

    /**
     * Get tasks older than specified days.
     */
    public function getOldTasks(int $days = 30): Collection
    {
        return $this->model
            ->where('created_at', '<', now()->subDays($days))
            ->get();
    }

    /**
     * Delete tasks older than specified days.
     */
    public function deleteOldTasks(int $days = 30): int
    {
        $count = $this->model
            ->where('created_at', '<', now()->subDays($days))
            ->count();

        $this->model
            ->where('created_at', '<', now()->subDays($days))
            ->delete();

        // Clear all task caches
        $this->clearAllTasksCache();

        return $count;
    }

    /**
     * Get all tasks with users (for admin).
     */
    public function getAllTasksWithUsers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with('user')
            ->ordered()
            ->paginate($perPage);
    }

    /**
     * Clear tasks cache for a specific user.
     */
    protected function clearUserTasksCache(int $userId): void
    {
        $cachePattern = "user_tasks_{$userId}_*";
        // In a real application, you might want to use a more sophisticated cache tagging system
        Cache::flush(); // For simplicity, we'll flush all cache
    }

    /**
     * Clear all tasks cache.
     */
    protected function clearAllTasksCache(): void
    {
        Cache::flush();
    }

    /**
     * Get recent tasks with users.
     */
    public function getRecent(int $limit = 10): Collection
    {
        return $this->model->with('user')->latest()->limit($limit)->get();
    }
}
