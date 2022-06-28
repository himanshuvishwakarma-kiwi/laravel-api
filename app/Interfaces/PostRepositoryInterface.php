<?php

namespace App\Interfaces;

interface PostRepositoryInterface 
{
    public function getAll();
    public function getPostById($id);
    public function deletePost($id);
    public function createPost(array $details);
    public function updatePost($id, array $newDetails);
}