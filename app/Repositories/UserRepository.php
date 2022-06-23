<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface 
{  
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(User $model)
    {
        $this->model = $model;
    }
    // public function getAllUsers() 
    // {
    //     return User::all();
    // }

    // public function getUserById($userId) 
    // {
    //     return User::findOrFail($userId);
    // }

    // public function deleteUser($userId) 
    // {
    //     User::destroy($userId);
    // }

    public function createUser(array $userDetails) 
    {
        return User::create($userDetails);
    }
    public function getModel()
    {
        return $this->model;
    }
    // public function updateUser($userId, array $newDetails) 
    // {
    //     return User::whereId($userId)->update($newDetails);
    // }
}