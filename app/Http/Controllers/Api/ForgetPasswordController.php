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
     /**
    * @OA\Post(
    *   path="/api/forgot-password",
    *   operationId="forgotpassword",
    *   tags={"Reset Password"},
    *   summary="Reset Password",
    *   description="User Forget Password",
    *   @OA\RequestBody(
    *      @OA\JsonContent(
    *         required={"email"},
    *         @OA\Property(property="email", type="string", format="email"),
    *      ),
    *      @OA\MediaType(
    *         mediaType="multipart/form-data",
    *         @OA\Schema(
    *           type="object",
    *           required={"email"},
    *             @OA\Property(property="email", type="text"),
    *         ),
    *      ),
    *   ),
    *   @OA\Response(
    *      response=200,
    *      description="Success",
    *   ),
    *   @OA\Response(
    *       response=400,
    *       description="Invalid request",
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="Not found",
    *   ),
    *   @OA\Response(
    *      response=401,
    *      description="Unauthorized",
    *   ),
    * )
    */
    // function for send password reset link
    public function sendPasswordResetLink(Request $request){
        $input = $request->only('email');
        $validator = Validator::make($input, [
            'email' => "required|email"
        ]);
        if ($validator->fails()) {
            return response()->json(['success'=>false,'message' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
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
    //End

    /**
    * @OA\Post(
    *   path="/api/reset-password/{token}",
    *   tags={"Reset Password"},
    *   summary="Reset Password",
    *   operationId="resetpassword",
    *   @OA\Parameter(
    *       name="token",
    *       in="path",
    *       required=true,
    *       @OA\Schema(
    *         type="string"
    *       )
    *   ),
    *   @OA\RequestBody(
    *       @OA\JsonContent(
    *          required={"password","password_confirmation"},
    *          @OA\Property(property="password", type="string", format="password"),
    *          @OA\Property(property="password_confirmation", type="password", format="password"),
    *       ),
    *       @OA\MediaType(
    *          mediaType="multipart/form-data",
    *          @OA\Schema(
    *              type="object",
    *              required={"password", "password_confirmation"},
    *              @OA\Property(property="password", type="password"),
    *              @OA\Property(property="password_confirmation", type="password")
    *          ),
    *       ),
    *   ),
    *   @OA\Response(
    *      response=200,
    *      description="Success",
    *   ),
    *   @OA\Response(
    *       response=400,
    *       description="Invalid request",
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="Not found",
    *   ),
    *   @OA\Response(
    *      response=401,
    *      description="Unauthorized",
    *   ),
    * )
    */
    // function reset password
    public function resetPassword(Request $request ,$token){
        $inputData = $request->only('password', 'password_confirmation');
        $validator = Validator::make($inputData, [
                'password'=>'required|min:8',
                'password_confirmation'=>'required|same:password'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['success'=>false,'message' => $validator->messages()],  Response::HTTP_UNPROCESSABLE_ENTITY);
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
    //End
}
