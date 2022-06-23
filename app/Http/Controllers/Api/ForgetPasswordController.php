<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        $user = $this->userRepository->getModel()->whereEmail($request->email)->first();

        if (!$user) {
            return response()->json(['error'=>true,'message' => trans('passwords.user')],Response::HTTP_NOT_FOUND);
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => JWTAuth::fromUser($user)
            ]
        );
    }
}
