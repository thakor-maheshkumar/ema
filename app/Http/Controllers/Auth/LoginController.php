<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;
use App\User;
use Auth;
use App\Country;
use App\CoreSetting;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDynamicEmail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function username(){
        return 'username';
    }

    public function login(Request $request)
    {

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendSuspendedResponse($request);

            //  $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }else{
               /* check user is available */
               $checkUserExists = User::where('username',$request->username)->with('roles')->first();
               /* check user is available */

               if(!empty($checkUserExists)){

                   $moduleName = 'user';
                   $moduleActivity = 'User Logged in fail';
                   $description = "Login failed of ".ucfirst($checkUserExists->name)." (".getUserRoles($checkUserExists->roles->first()->name).') due to invalid credentials';
                   $requestData =array('user_id'=>$checkUserExists->id);

                   /* Get Company name */
                   $companyName = getUserCompanyName($checkUserExists);
                   /* Get Company name */
               }else{
                   $moduleName = 'user';
                   $moduleActivity = 'User Logged in fail';
                //    $description = "Login failed of ".ucfirst($request->username)." (anonymous) due to user dose not exists in the system.";
                    $description = "User does not exists in system, Login failed";
                   $requestData =array('user_id'=>'0');

                   /* Get Company name */
                   $companyName = 'Anonymous';
                   /* Get Company name */
               }

               captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $user->is_logged_in = 1;
        $user->last_login_activity = \Carbon\Carbon::now();
        $user->save();

        $setting = CoreSetting::where('name','inactivity_session_time')->first();
        $request->session()->put('inactivity_session_time', $setting->value);

        $setting_force_change = CoreSetting::where('name','force_password_change')->first();
        $request->session()->put('force_password_change', $setting_force_change->value);
    }

    protected function sendSuspendedResponse(Request $request)
    {
        $suspended_user = User::where('username',$request->input('username'))->first();
        if($suspended_user){
            $suspended_user->status = 3;
            $suspended_user->save();

            $moduleName = 'user';
            $moduleActivity = 'User Suspended';
            $description = ucfirst($suspended_user->name)." (".getUserRoles($suspended_user->roles->first()->name).') has been suspended due to exceeded of maximum login attempts with wrong credentials.';
            $requestData =array('user_id'=>$suspended_user->id);

            /* Get Company name */
                $companyName = getUserCompanyName($suspended_user);
            /* Get Company name */

            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);

            $data['slug'] = 'suspend_user_account';
            $data['name'] = $suspended_user->name;
            Mail::to($suspended_user->email)->queue(new SendDynamicEmail($data));

            $moduleName = 'Email';
            $moduleActivity = 'Email logged for suspended user';
            $description = 'Email has been sent to suspended user : '.ucfirst($suspended_user->name).".";
            $requestData = array('user_id'=>$suspended_user->id);

            /*Add action in audit log*/
                captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
            /*Add action in audit log*/
        }

        throw ValidationException::withMessages([
            $this->username() => [Lang::get('auth.suspend')],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }

    public function showLoginForm()
    {
        /* Get country data */
            $getCountryData = Country::orderBy('name')->get();
         /* Get country data */
        return view('welcome',compact('getCountryData'));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        $usercount = \DB::table('sessions')->select('user_id')->where('user_id',Auth::user()->id)->count();
        if($usercount == 1){
            $user = Auth::user();
            $user->is_logged_in = 0;
            $user->save();
        }

        $moduleName = 'user';
        $moduleActivity = 'User Logged out';
        // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has successfully logged out.';
        $description = "User Logged out";
        $requestData =array('user_id'=>Auth::user()->id);

        /* Get Company name */
           $companyName = getUserCompanyName(Auth::user());
        /* Get Company name */

        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/dashboard');
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        $setting = CoreSetting::where('name','login_attempt')->first();
        $attempts = $setting['value'] - 1;
        $data = $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $attempts // default is $this->maxAttempts()
        );
        return $data;
    }

    protected function redirectTo()
    {
        $setting = CoreSetting::where('name','force_password_change')->first();
        if(Auth::user()->status==1){
            $moduleName = 'user';
            $moduleActivity = 'User Logged in';
            // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has successfully logged in.';
            $description = "User Logged in";
            $requestData =array('user_id'=>Auth::user()->id);

            /* Get Company name */
            $companyName = getUserCompanyName(Auth::user());
            /* Get Company name */

            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
        }

        if(Auth::user()->first_time_login === 0 && $setting->value === 1){
            return '/change_password';
        }else{
            return '/dashboard';
        }
    }


    protected function sendLoginResponse(Request $request){
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        return $this->authenticated($request, $this->guard()->user())
            ?: redirect($this->redirectPath());
    }
}
