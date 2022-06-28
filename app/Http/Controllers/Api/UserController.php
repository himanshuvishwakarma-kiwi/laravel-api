<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    //function for creating new user
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:2',
            'last_name' => 'required|string|min:2',
            'phone' => 'required|string|min:10',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
            'confirm_password' => 'required|string|same:password|min:6|max:50'
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $userDetails = $request->except(['password','confirm_password']);
        $userDetails['password'] = bcrypt($request->password);
        try {
            $user = $this->userRepository->createUser($userDetails);
            // User created, return success response
            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => $user
            ], Response::HTTP_OK);    
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // function for login  
    public function login(Request $request){
        $userCredentials = $request->only('email','password');
        //check valid user input
        $validator = Validator::make($userCredentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);
        //Send failed response when request is not valid
        if($validator->fails()){
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Create token        
        try {
            $user = $this->userRepository->getModel()->whereEmail($request->email)->first();
            if($user){
                $token = JWTAuth::attempt($userCredentials);
                if (! $token = JWTAuth::attempt($userCredentials)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Login credentials are invalid.',
                    ], 400);
                }
            }else{  
                return response()->json([
                	'success' => false,
                	'message' => 'Email doesn\'t found in our database.',
                ], 400);
            }
        } catch (JWTException $e) {
    	    return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
            ], 500);
        }
 	
 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
    // get profile
    public function getUser(Request $request){
        
        $token = $request->bearerToken();

        $user = JWTAuth::authenticate($token);
    
        return response()->json(['user' => $user]);
    }
    //for logout
    public function logout(Request $request){
        $token = $request->bearerToken();
		//Request is validated, user logout        
        try {
            JWTAuth::invalidate($token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    //for user profile update
    public function profileUpdate(Request $request,$userId){
        //check valid user input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:2',
            'last_name' => 'required|string|min:3',
            'phone' => 'required|string|min:10',
        ]);
        //Send failed response when request is not valid
        if($validator->fails()){
            return response()->json(['error' => $validator->messages()], 200);
        }
        $userData = $this->userRepository->getUserById($userId);
        if($userData){
            try {
                $this->userRepository->updateUser($userId,$request->all());
                return response()->json([
                    'success' => true,
                    'message' => 'User details has been updated successfully.',
                ], Response::HTTP_OK); 
            } catch (\Exception $e) {
                return response(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json( [
                'success' => false,
                'message' => 'User data not found.'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    //for delete user 
    public function deleteUser($userId)
    {
        if($userId == JWTAuth::user()->id){
            return response()->json( [
                'success' => false,
                'message' => 'You are not allowed to delete that user'
            ]);
        }
        try {
            $userData = $this->userRepository->getUserById($userId);
            if($userData){
                $this->userRepository->deleteUser($userId);
                return response()->json( [
                    'success' => true,
                    'message' => 'Account has been permanently removed from the system.'
                ],Response::HTTP_OK);
            }else{
                return response()->json( [
                    'success' => false,
                    'message' => 'User account not found.'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    //get all user
    public function getAllusers(){
        try {
            $userSData = $this->userRepository->getAllUsers();
            return response()->json( [
                'success' => true,
                'message'=>'',
                'data' => $userSData
            ],Response::HTTP_OK);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
