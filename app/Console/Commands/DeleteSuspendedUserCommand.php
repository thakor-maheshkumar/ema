<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDynamicEmail;

class DeleteSuspendedUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deletesuspended:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete suspended users based on coresettings days';

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
        $core_setting_days = \App\CoreSetting::where('name','delete_suspended_user')->where('status',1)->first();
        $users = User::where('status',3)->where('updated_at','<', \Carbon\Carbon::now()->subDays($core_setting_days->value)->toDateTimeString())->with('roles')->get();

        if (count($users) == 0) {
            $this->error("Users not found");
            return;
        }

        foreach($users as $user){
            if($user->roles[0]->id != 1){
                $this->info("The user is : $user->username");
                $user->status = 2;
                $user->save();
                $user->delete();

                $moduleName = 'Schedular';
                $moduleActivity = 'Deleted suspended user';
                $description = 'System has deleted suspended '.ucfirst($user->name)." (".getUserRoles($user->roles->first()->name).")";
                $requestData = array('user_id'=>$user->id);

                /*Add action in audit log*/
                    captureAuditLog($moduleName,$moduleActivity,$description,$requestData);
                /*Add action in audit log*/

                $data['slug'] = 'delete_user';
                $data['name'] = $user->name;
                Mail::to($user->email)->queue(new SendDynamicEmail($data));

                /* Get Company name of on which user activity was performed */
                    $getUserCompanyname = getUserCompanyName($user);
                    $companyType = getUserCompanyType($user);
                /* Get Company name of on which user activity was performed */

                $moduleName = 'Schedular';
                $moduleActivity = 'System deleted suspend user mail';
                // $description = 'System deleted suspended user mail for '.$user->name.".";
                $description = "Deleted user email has been automatically sent to ".ucfirst($user->name)." (".getUserRoles($user->roles->first()->name).") for ".$companyType." ".$getUserCompanyname."";
                $requestData = array('user_id'=>$user->id);

                /*Add action in audit log*/
                    captureAuditLog($moduleName,$moduleActivity,$description,$requestData);
                /*Add action in audit log*/

            }
        }
    }
}
