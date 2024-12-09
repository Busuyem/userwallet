<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * @param  array  $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return User::create($data);
    }

    /**
     * Get a user by their ID.
     *
     * @param  int  $id
     * @throws ModelNotFoundException
     * @return User
     */
    public function getUserById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Get all users with pagination.
     *
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function getAllUsersByPagination(int $perPage = 15): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }
}
