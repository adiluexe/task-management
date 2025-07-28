<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user.
     */
    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = $data['is_admin'] ?? false;

        return $this->userRepository->create($data);
    }

    /**
     * Login user and return token.
     */
    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw new \Exception('Invalid credentials');
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout user.
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Get all users with task statistics (admin only).
     */
    public function getAllUsersWithTaskStats(int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->getAllUsersWithTaskStats($perPage);
    }

    /**
     * Get user with task statistics.
     */
    public function getUserWithTaskStats(int $userId): ?User
    {
        return $this->userRepository->getUserWithTaskStats($userId);
    }
}
