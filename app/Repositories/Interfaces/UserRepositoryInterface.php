<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface UserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * @param  array  $data
     * @return User
     */
    public function createUser(array $data): User;

    /**
     * Get a user by their ID.
     *
     * @param  int  $id
     * @throws ModelNotFoundException If the user is not found.
     * @return User
     */
    public function getUserById(int $id): User;

    /**
     * Get all users with pagination.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getAllUsersByPagination(int $perPage = 15): LengthAwarePaginator;
}
