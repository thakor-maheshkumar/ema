<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LogoutUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (auth()->check() && $user->is_logged_in == 0) {
            // Log user out
            Auth::logout();
            $notification = array(
                'message' => 'User has been logged out',
                'alert-type' => 'error',
                'data'=>'Unauthenticated.'
            );
            if($request->ajax()){
                return response()->json($notification, 401);
            }else{
                return  redirect()->route('login');
            }
        }
        return $next($request);
    }
}
