<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\TaskService;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly TaskService $taskService,
        private readonly UserRepository $userRepository
    ) {
    }

    /**
     * Get admin dashboard statistics.
     */
    public function getDashboardStats(): JsonResponse
    {
        $user = auth()->user();

        // Better debugging information
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated',
                'debug' => [
                    'auth_check' => auth()->check(),
                    'token_present' => request()->hasHeader('Authorization')
                ]
            ], 401);
        }

        // Show detailed user info for debugging
        $debugInfo = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'is_admin_field' => $user->is_admin,
            'isAdmin_method' => $user->isAdmin(),
            'user_attributes' => $user->getAttributes(),
        ];

        if (!$user->isAdmin()) {
            return response()->json([
                'message' => 'Access denied. Admin privileges required.',
                'debug' => $debugInfo
            ], 403);
        }

        try {
            // Get all users count
            $totalUsers = $this->userRepository->count();

            // Get all tasks statistics using admin method
            $allTasksPaginated = $this->taskService->getAllTasksWithUsers(1000); // Get a large number to count all
            $allTasks = $allTasksPaginated->getCollection();
            $totalTasks = $allTasks->count();
            $completedTasks = $allTasks->where('status', 'completed')->count();
            $pendingTasks = $allTasks->where('status', 'pending')->count();

            // Calculate completion rate
            $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            return response()->json([
                'stats' => [
                    'totalUsers' => $totalUsers,
                    'totalTasks' => $totalTasks,
                    'completedTasks' => $completedTasks,
                    'pendingTasks' => $pendingTasks,
                    'completionRate' => $completionRate,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch dashboard stats'], 500);
        }
    }

    /**
     * Get all users with task statistics.
     */
    public function getUsers(Request $request): JsonResponse
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $perPage = $request->get('per_page', 15);
            $users = $this->authService->getAllUsersWithTaskStats($perPage);

            // Add task counts to each user
            $users->getCollection()->transform(function ($user) {
                $user->tasks_count = $user->tasks->count();
                $user->completed_tasks = $user->tasks->where('status', 'completed')->count();
                $user->pending_tasks = $user->tasks->where('status', 'pending')->count();
                unset($user->tasks); // Remove tasks data to keep response clean
                return $user;
            });

            return response()->json([
                'users' => UserResource::collection($users->items()),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch users'], 500);
        }
    }

    /**
     * Toggle admin status for a user.
     */
    public function toggleAdminStatus(Request $request, int $userId): JsonResponse
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Prevent self-demotion
        if (auth()->user()->id === $userId) {
            return response()->json(['message' => 'Cannot change your own admin status'], 400);
        }

        try {
            $user = $this->userRepository->findById($userId);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user = $this->userRepository->update($user, [
                'is_admin' => !$user->is_admin
            ]);

            return response()->json([
                'message' => $user->is_admin ? 'User promoted to admin' : 'Admin privileges removed',
                'user' => new UserResource($user)
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user'], 500);
        }
    }

    /**
     * Delete a user and their tasks.
     */
    public function deleteUser(int $userId): JsonResponse
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Prevent self-deletion
        if (auth()->user()->id === $userId) {
            return response()->json(['message' => 'Cannot delete your own account'], 400);
        }

        try {
            $user = $this->userRepository->findById($userId);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Delete user (tasks will be deleted due to cascade)
            $this->userRepository->delete($user);

            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete user'], 500);
        }
    }

    /**
     * Get recent activity for admin dashboard.
     */
    public function getRecentActivity(): JsonResponse
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            // Get recent users (last 10)
            $recentUsers = $this->userRepository->getRecent(5);

            // Get recent tasks (last 10)
            $recentTasks = $this->taskService->getRecentTasks(5);

            $activity = collect();

            // Add user registrations to activity
            foreach ($recentUsers as $user) {
                $activity->push([
                    'id' => 'user_' . $user->id,
                    'type' => 'user_created',
                    'description' => "New user {$user->name} registered",
                    'created_at' => $user->created_at,
                ]);
            }

            // Add task activities
            foreach ($recentTasks as $task) {
                $activity->push([
                    'id' => 'task_' . $task->id,
                    'type' => $task->status === 'completed' ? 'task_completed' : 'task_created',
                    'description' => $task->status === 'completed'
                        ? "Task \"{$task->title}\" completed by {$task->user->name}"
                        : "Task \"{$task->title}\" created by {$task->user->name}",
                    'created_at' => $task->updated_at,
                ]);
            }

            // Sort by date descending and take top 10
            $activity = $activity->sortByDesc('created_at')->take(10)->values();

            return response()->json(['activity' => $activity]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch recent activity'], 500);
        }
    }
}
