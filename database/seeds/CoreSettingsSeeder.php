<?php

use Illuminate\Database\Seeder;
use App\CoreSetting;
class CoreSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     public function run()

     {

    CoreSetting::FirstOrCreate([
        'name' => 'inactivity_session_time',
        'value' =>1,
        'created_by'=>1,
        'ip_address'=> request()->ip(),
    ]);

   CoreSetting::FirstOrCreate([
       'name' => 'minimum_password_length',
       'value' =>8,
       'created_by'=>1,
       'ip_address'=> request()->ip(),
   ]);

   CoreSetting::FirstOrCreate([
       'name' => 'force_password_change',
       'value' =>0,
       'created_by'=>1,
       'ip_address'=> request()->ip(),
   ]);

   CoreSetting::FirstOrCreate([
       'name' => 'login_attempt',
       'value' =>5,
       'created_by'=>1,
       'ip_address'=> request()->ip(),
   ]);

   CoreSetting::FirstOrCreate([
       'name' => 'inactive_user_suspension',
       'value' =>90,
       'created_by'=>1,
       'ip_address'=> request()->ip(),
   ]);

   CoreSetting::FirstOrCreate([
       'name' => 'delete_suspended_user',
       'value' =>30,
       'created_by'=>1,
       'ip_address'=> request()->ip(),
   ]);

    CoreSetting::FirstOrCreate([
       'name' => 'solution_bottle_pack',
       'value' =>4,
       'created_by'=>1,
       'ip_address'=> request()->ip(),
    ]);

    CoreSetting::FirstOrCreate([
        'name' => 'cosmetic_fresh_pack',
        'value' =>6,
        'created_by'=>1,
        'ip_address'=> request()->ip(),
    ]);

    CoreSetting::FirstOrCreate([
        'name' => 'booster_packs',
        'value' =>6,
        'created_by'=>1,
        'ip_address'=> request()->ip(),
    ]);

    CoreSetting::FirstOrCreate([
        'name' => 'aquaB_tips',
        'value' =>6,
        'created_by'=>1,
        'ip_address'=> request()->ip(),
    ]);
    CoreSetting::FirstOrCreate([
        'name' => 'contact_forward_email',
        'value' =>'info@emaltd.co',
        'created_by'=>1,
        'ip_address'=> request()->ip(),
    ]);

}
}
