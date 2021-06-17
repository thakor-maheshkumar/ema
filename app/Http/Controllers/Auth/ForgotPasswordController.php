<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\User;
use Illuminate\Support\Str;
use App\Mail\SendDynamicEmail;
use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
            $this->validate($request, ['forgot_username' => 'required'], ['forgot_username.required' => 'Please enter your username.']);

            $request['username'] = $request['forgot_username'];

            $response = $this->broker()->sendResetLink(
                $request->only('username')
            );

            return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
                ->withInput($request->only('forgot_username'))
                ->withErrors(['forgot_username' => trans($response)]);
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, ['forgot_username' => 'required'], ['forgot_username.required' => 'Please enter your username.']);
        $input = $request->all();
        if (User::where('username', '=', $input['forgot_username'])->whereNotIn('status',[2,3])->exists()) {
            $user = User::where('username', '=', $input['forgot_username'])->with('roles')->first();
            $password = Str::random(10);
            $new_password = bcrypt($password);

            $users = User::find($user->id);
            $users->password = $new_password;
            $users->first_time_login = 0;
            $users->is_logged_in = 0;
            $users->save();


            $moduleName = 'Forgot Passowrd';
            $moduleActivity = 'Request for new password';
            // $description = "Login failed of ".ucfirst($user->name)." (".getUserRoles($user->roles->first()->name).') has requested for new password.';
            $description = "New Password Request has been generated";
            $requestData =array('user_id'=>$user->id);

            /* Get Company name */
            $companyName = getUserCompanyName($user);
            /* Get Company name */

            /*Start - Add action in audit log*/
            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
            /*End - Add action in audit log*/

            $data['slug'] = 'forgot_password';
            $data['name'] = $users->name;
            $data['new_password'] =  $password;

            Mail::to($user->email)->queue(new SendDynamicEmail($data));

            $moduleName = 'Email';
            $moduleActivity = 'Email logged for forgot password';
            $description = 'Email has been sent to user for forgot password: '.ucfirst($user->name).".";
            $requestData = array('user_id'=>$user->id);

            /*Add action in audit log*/
                captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
            /*Add action in audit log*/


            /* AT-2049 - Send SMS for reset password */
            $template_slug = 'reset_password';
            $recipient = $users->mobile_telephone_number;
            SendTwilioSMS($recipient, $template_slug);

            $notification = array(
                'message' => 'Password has been sent on your email address!',
                'alert-type' => 'success'
            );
            return back()->with($notification);
        }else{

            $moduleName = 'Forgot Passowrd';
            $moduleActivity = 'Request for new password';
            $description = ucfirst($input['forgot_username'])." (anonymous) has requested for new password but user dose not exists in the system.";
            $requestData =array('user_id'=>'0');

            /* Get Company name */
            $companyName = 'Anonymous';
            /* Get Company name */

            /*Start - Add action in audit log*/
            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
            /*End - Add action in audit log*/

            return back()
                ->withInput($request->only('forgot_username'))
                ->withErrors(['forgot_username' => 'Password has been sent on your email address!']);
        }

    }
}
