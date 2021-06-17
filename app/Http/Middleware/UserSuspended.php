<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class UserSuspended
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

        if (auth()->check() && auth()->user()->status == 3) {

            $users = User::find(auth()->user()->id);
            $users->is_logged_in = 0;
            $users->save();

            $notification = array(
                'message' => 'Your account has been suspended. Please contact administrator!',
                'alert-type' => 'error',
                'data'=>'Unauthenticated.'
            );

            $moduleName = 'user';
            $moduleActivity = 'User Logged in fail';
            $description = "Login failed of ".ucfirst(auth()->user()->name)." (".getUserRoles(auth()->user()->roles->first()->name).') due to account suspended';
            $requestData =array('user_id'=>auth()->user()->id);

            /* Get Company name */
            $companyName = getUserCompanyName(auth()->user());
            /* Get Company name */
            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);


            auth()->logout();

            if($request->ajax()){
                return response()->json($notification, 401);
            }else{
                return  redirect()->route('login')->with($notification);
            }

        }else if(auth()->check() && auth()->user()->status == 2){

            $users = User::find(auth()->user()->id);
            $users->is_logged_in = 0;
            $users->save();

            $notification = array(
                'message' => 'This account has been deleted.',
                'alert-type' => 'error',
                'data'=>'Unauthenticated.'
            );

            $moduleName = 'user';
            $moduleActivity = 'User Logged in fail';
            $description = "Login failed of ".ucfirst(auth()->user()->name)." (".getUserRoles(auth()->user()->roles->first()->name).') due to account deleted';
            $requestData =array('user_id'=>auth()->user()->id);

            /* Get Company name */
                $companyName = getUserCompanyName(auth()->user());
            /* Get Company name */
            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);

            $users->delete();
            auth()->logout();

            if($request->ajax()){
                return response()->json($notification, 401);
            }else{
                return  redirect()->route('login')->with($notification);
            }
        }else if(auth()->check() && auth()->user()->is_logged_in == 0){
            $users = User::find(auth()->user()->id);
            $users->is_logged_in = 0;
            $users->save();
            $passwordUpdate =  \Cookie::get('passwordUpdate');
            if(isset($passwordUpdate) && $passwordUpdate==1){
                $notification = array(
                    'message' => 'Password updated successfully',
                    'alert-type' => 'success',
                );

            }else{
                $notification = array(
                    'message' => 'You have been logged out. Please check your email for more information',
                    'alert-type' => 'success',
                    'data'=>'Unauthenticated.'
                );
            }

            $moduleName = 'user';
            $moduleActivity = 'User Logged in fail';
            $description = "Login failed of ".ucfirst(auth()->user()->name)." (".getUserRoles(auth()->user()->roles->first()->name).') due to forced logged out or force reset password';
            $requestData =array('user_id'=>auth()->user()->id);

            /* Get Company name */
                $companyName = getUserCompanyName(auth()->user());
            /* Get Company name */
            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);

            // $update_session = \DB::table('sessions')->where('user_id',auth()->user()->id)->delete();
            auth()->logout();

            if($request->ajax()){
                return response()->json($notification, 401);
            }else{
                return  redirect()->route('login')->with($notification)->withCookie(\Cookie::forget('passwordUpdate'));
            }
        }
        return $next($request);
    }
}