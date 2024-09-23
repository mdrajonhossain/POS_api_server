<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class admin_user
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next){
        $user = $request->attributes->get('user');

        if($user->is_type == 'is_admin'){
            return $next($request);
        }else{
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }
    }
}
