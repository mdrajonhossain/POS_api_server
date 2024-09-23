<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;


class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next){
        $token = $request->bearerToken();
        
        if (!$token) {
            // return response()->json(['error' => 'Unauthorized'], 401);
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }            
            
        $accessToken = PersonalAccessToken::where('token', hash('sha256', $token))->first();
        if (!$accessToken || !$accessToken->tokenable) {
            // return response()->json(['error' => 'Unauthorized'], 401);

            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }

        Auth::login($accessToken->tokenable);        
        $request->attributes->set('user', $accessToken->tokenable);

        return $next($request);        
    }
}