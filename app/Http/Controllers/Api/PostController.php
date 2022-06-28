<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository) 
    {
        $this->postRepository = $postRepository;
    }
    //get all post
    public function index(){
        try {
            $data = $this->postRepository->getAll();
            return response()->json([
                'success' => true,
                'message' => '',
                'data' => $data
            ], Response::HTTP_OK);    
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function createPost(Request $request)
    {
        $input = $request->all();
        //check valid input
        $validator = Validator::make($input, [
            'title' => 'required|string|min:2',
            'description' => 'required|string|min:3',
        ]);

        //Send failed response when request is not valid
        if($validator->fails()){
            return response()->json(['error' => $validator->messages()], 200);
        }
        try {
            $this->postRepository->createPost($input);
            // Post created, return success response
            return response()->json([
                'success' => true,
                'message' => 'Post created successfully.',
            ], Response::HTTP_OK);    
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
    * Display the specified post by id.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $postData = $this->postRepository->getPostById($id);
        if (is_null($postData)) {
            return response()->json([
                "success" => false,
                "message" => "Post not found.",
                "data" => NULL
            ],Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            "success" => true,
            "message" => "Post retrieved successfully.",
            "data" => $postData
        ]);
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function updatePost($postId, Request $request)
    {
        $postData = $this->postRepository->getPostById($postId);
        if (is_null($postData)) {
            return response()->json([
                "success" => false,
                "message" => "Post not found.",
                "data" => NULL
            ],Response::HTTP_NOT_FOUND);
        }
        $input = $request->all();
        //check valid input
        $validator = Validator::make($input, [
            'title' => 'required|string|min:2',
            'description' => 'required|string|min:3',
        ]);

        //Send failed response when request is not valid
        if($validator->fails()){
            return response()->json(['error' => $validator->messages()], 200);
        }
        try {
            $this->postRepository->updatePost($postId,$input);
            // Post created, return success response
            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully.',
            ], Response::HTTP_OK);    
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    /**
    * Remove the specified post from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deletePost($postId)
    {
        try {
            $postData = $this->postRepository->getPostById($postId);
            if($postData){
                $this->postRepository->deletePost($postId);
                return response()->json( [
                    'success' => true,
                    'message' => 'Post deleted successfully..'
                ],Response::HTTP_OK);
            }else{
                return response()->json( [
                    'success' => false,
                    'message' => 'Post not found.'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
