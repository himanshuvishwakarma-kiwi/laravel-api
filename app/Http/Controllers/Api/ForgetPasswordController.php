<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordReset;

class ForgetPasswordController extends Controller
{
    protected $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    // function for send password reset link
    public function sendPasswordResetLink(Request $request){
        $input = $request->only('email');
        $validator = Validator::make($input, [
            'email' => "required|email"
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = $this->userRepository->getModel()->where( 'email', $request->email )->first();
        if (!$user ) {
            return response()->json( [
                'success' => false,
                'message' =>  __('passwords.user')
            ], Response::HTTP_NOT_FOUND );
        }
        $passwordReset = PasswordReset::updateOrCreate(
            [ 'email' => $user->email ],
            [
               'email' => $user->email,
               'token' => Str::random(64)
            ]
        );
        if ($user && $passwordReset ) {
            $user->notify(
               new \App\Notifications\ResetPasswordNotifciation($passwordReset->token)
            );
        }
        return response()->json( [
            'success' => true,
            'message' =>  __('passwords.sent')
        ],Response::HTTP_OK );
        
    }
    // function reset password
    public function resetPassword(Request $request ,$token){
        $inputData = $request->only('password', 'password_confirmation');
        $validator = Validator::make($inputData, [
            'password'=>'required|min:8',
            'password_confirmation'=>'required|same:password'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if ( ! $passwordReset ) {
            return response()->json( [
                'success' => false,
                'message' => 'This Password Reset token is invalid.'
            ], Response::HTTP_NOT_FOUND);
        }
        $userEmail = PasswordReset::where('token', $passwordReset->token)->pluck('email');
        $user      = $this->userRepository->getModel()->where( 'email', $userEmail )->first();
        if($user){
            $user->password = bcrypt($request->password);
            $user->save();
            $passwordReset->delete();
        }
        return response()->json( [
            'success' => true,
            'message'=> trans('passwords.change')
        ],Response::HTTP_OK);
    }
}
