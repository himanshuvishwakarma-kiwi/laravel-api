<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {   
        $request->header('Authorization',"Bearer ".$request->bearerToken());
        if(!empty($request->bearerToken())){
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json(['message' => 'Token is Invalid.','status'=>Response::HTTP_BAD_REQUEST],Response::HTTP_BAD_REQUEST);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json(['message' => 'Token is Expired.','status'=>Response::HTTP_OK], Response::HTTP_OK);
                }else{
                    return response()->json(['message' => 'Authorization Token not found.','status' => Response::HTTP_UNAUTHORIZED], Response::HTTP_UNAUTHORIZED);
                }
            }
        }else{
            return response()->json(['message' => 'Unauthorized access.','status' => Response::HTTP_UNAUTHORIZED], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
