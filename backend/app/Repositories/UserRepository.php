<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new user.
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Find user by ID.
     */
    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Get all users with their task statistics (for admin).
     */
    public function getAllUsersWithTaskStats(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with([
                'tasks' => function ($query) {
                    $query->select('id', 'user_id', 'status');
                }
            ])
            ->paginate($perPage);
    }

    /**
     * Get user with task statistics.
     */
    public function getUserWithTaskStats(int $userId): ?User
    {
        return $this->model
            ->with([
                'tasks' => function ($query) {
                    $query->select('id', 'user_id', 'status');
                }
            ])
            ->find($userId);
    }

    /**
     * Update user.
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    /**
     * Delete user.
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Count total users.
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Get recent users.
     */
    public function getRecent(int $limit = 10): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }
}
