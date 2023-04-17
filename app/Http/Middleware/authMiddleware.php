<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class authMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $rawHeader = $request->header('Authorization');
        $arrayRawHeader = explode(" ", $rawHeader);
        if ($rawHeader != null && $arrayRawHeader[1] !== "" ) 
        {
            try {
                JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'message' => $e->getMessage(),
                    'data' => null
                ], 400);
            }
        }
        else
        {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'token required',
                'data' => null
            ], 400);
        }
        return $next($request);
    }
}
