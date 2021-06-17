<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDynamicEmail;

class SuspendUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suspend:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Suspend User who have not accessed their account in three months';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $core_setting_days = \App\CoreSetting::where('name','inactive_user_suspension')->where('status',1)->first();
        $users = User::whereNotIn('status',[2,3])
        ->where(function($query) use($core_setting_days){
            $query->where('last_login_activity','<', \Carbon\Carbon::now()->subDays($core_setting_days->value)->toDateTimeString());
            $query->orWhereNull('last_login_activity');
        })->with('roles')->get();

        if (empty($users)) {
            $this->error("User not found");
            return;
        }

        foreach($users as $user){
            if($user->roles[0]->id != 1){
                $usercompanyName = getUserCompanyName($user);
                if($user->last_login_activity == NULL){
                    if($user->created_at < \Carbon\Carbon::now()->subDays($core_setting_days->value)){
                        $this->info("The user is : $user->username");
                        $user->status = 3;
                        $user->save();

                        $moduleName = 'Schedular';
                        $moduleActivity = 'Suspended user';
                        $description = 'System has suspended '.$user->name." (".getUserRoles($user->roles->first()->name).") user due to not accessed account since ".$core_setting_days->value." days";
                        $requestData = array('user_id'=>$user->id);

                        /*Add action in audit log*/
                            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                        /*Add action in audit log*/

                        $data['slug'] = 'suspend_user_account';
                        $data['name'] = $user->name;
                        Mail::to($user->email)->queue(new SendDynamicEmail($data));

                            /* Get Company name of on which user activity was performed */
                                $getUserCompanyname = getUserCompanyName($user);
                                $companyType = getUserCompanyType($user);
                            /* Get Company name of on which user activity was performed */


                        $moduleName = 'Schedular';
                        $moduleActivity = 'System suspend user mail';
                        // $description = 'System suspended mail generated for '.$user->name;
                        $description ="Suspend user email has been automatically sent to ".ucfirst($user->name)." (".getUserRoles($user->roles->first()->name).") for ".$companyType." ".$getUserCompanyname."";
                        $requestData = array('user_id'=>$user->id);

                        /*Add action in audit log*/
                            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                        /*Add action in audit log*/
                    }

                }else{
                    $this->info("The user is : $user->username");
                    $user->status = 3;
                    $user->save();

                    $moduleName = 'Schedular';
                    $moduleActivity = 'Suspended user';
                    $description = 'System has suspended '.$user->name." (".getUserRoles($user->roles->first()->name).") user due to not accessed account since ".$core_setting_days->value." days";
                    $requestData = array('user_id'=>$user->id);

                    /*Add action in audit log*/
                        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                    /*Add action in audit log*/

                    $data['slug'] = 'suspend_user_account';
                    $data['name'] = $user->name;
                    Mail::to($user->email)->queue(new SendDynamicEmail($data));

                    /* Get Company name of on which user activity was performed */
                      $getUserCompanyname = getUserCompanyName($user);
                      $companyType = getUserCompanyType($user);
                    /* Get Company name of on which user activity was performed */

                    $moduleName = 'Schedular';
                    $moduleActivity = 'System suspend user mail';
                    // $description = 'System suspended mail generated for '.$user->name;
                    $description ="Suspend user email has been automatically sent to ".ucfirst($user->name)." (".getUserRoles($user->roles->first()->name).") for ".$companyType." ".$getUserCompanyname."";
                    $requestData = array('user_id'=>$user->id);

                    /*Add action in audit log*/
                        captureAuditLog($moduleName,$moduleActivity,$description,$requestData);
                    /*Add action in audit log*/
                }

            }
        }
    }
}
