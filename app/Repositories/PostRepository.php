<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface 
{  
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Post $model)
    {
        $this->model = $model;
    }
    public function getAll() 
    {
        return $this->model->orderBy('id', 'desc')->get();
    }

    public function getPostById($id) 
    {
        return $this->model->find($id);
    }

    public function deletePost($id) 
    {
        $this->model->destroy($id);
    }

    public function createPost(array $details) 
    {
        return $this->model->create($details);
    }
    public function getModel()
    {
        return $this->model;
    }
    public function updatePost($id, array $newDetails) 
    {
        return $this->model->whereId($id)->update($newDetails);
    }
}