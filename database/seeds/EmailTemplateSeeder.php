<?php

use Illuminate\Database\Seeder;
use App\EmailTemplates;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailTemplates::firstOrCreate([
	       'name' => 'Welcome Email',
	       'slug' =>'welcome_email',
	       'subject'=>'EMA Asthetics - Registration',
	       'content'=>'<p>Dear {{name}},</p>
					<p>&nbsp;</p>
					<p>Your registration has been success.</p>
					<p>&nbsp;</p>
					<p>Please find below for the login details.</p>
					<p>&nbsp;</p>
					<p>Username :&nbsp; {{username}}</p>
					<p>One time Password : {{new_password}}</p>
					<p>&nbsp;</p>
					<p>Best regards,</p>
					<p>EMA Asthetics&nbsp;</p>'
   		]);

   		EmailTemplates::firstOrCreate([
	       'name' => 'Forgot Password',
	       'slug' =>'forgot_password',
	       'subject'=>'EMA Asthetics - Forgot Password',
	       'content'=>'<p>Hi {{name}},</p>
						<p>&nbsp;</p>
						<p>Please find below for the new password temporary password.</p>
						<p>&nbsp;</p>
						<p>Password is {{new_password}}&nbsp;</p>
						<p>&nbsp;</p>
						<p>Thanks,<br />EMA Asthetics</p>'
   		]);

   		EmailTemplates::firstOrCreate([
	       'name' => 'Contact US',
	       'slug' =>'contact_us',
	       'subject'=>'EMA Asthetics - Contact US',
	       'content'=>'<p>Dear {{first_name}},</p>
						<p>&nbsp;</p>
						<p>Thank you for contacting us. our executive will contact you.</p>
						<p>&nbsp;</p>
						<p>Best regards,</p>
						<p>EMA Asthetics</p>'
   		]);

   		EmailTemplates::firstOrCreate([
	       'name' => 'Admin Contact US',
	       'slug' =>'admin_contact_us',
	       'subject'=>'EMA Asthetics - new inquiry Contact US',
	       'content'=>'<p>Dear Admin,</p>
						<p>&nbsp;</p>
						<p>Please find below for the new inquiry contact US.</p>
						<p>&nbsp;</p>
						<ul>
						<li>
						<p>First Name : {{first_name}}</p>
						</li>
						<li>
						<p>LastName &nbsp;: {{last_name}}</p>
						</li>
						</ul>
						<p>&nbsp;</p>
						<ul>
						<li>Job Role &nbsp;: {{job_role}}</li>
						<li>Telephone Number : {{contact_telephone_number}}</li>
						<li>
						<p>Mobile Number : {{mobile_number}}</p>
						</li>
						</ul>
						<ul>
						<li>Message : {{message}}</li>
						</ul>
						<p>&nbsp;</p>
						<p>Thanks and Regards</p>
						<p>EMA Asthetics</p>'
   		]);

   		EmailTemplates::firstOrCreate([
	       'name' => 'User Update',
	       'slug' =>'user_update',
	       'subject'=>'EMA Asthetics - profile updated',
	       'content'=>'<p>Dear {{name}},</p>
						<p>&nbsp;</p>
						<p>Your following details has been updated.&nbsp;</p>
						<p>&nbsp;</p>
						<p>Name : {{name}}</p>
						<p>Telephone Number: {{primary_telephone_number}}</p>
						<p>Mobile Number: {{mobile_telephone_number}}</p>
						<p>&nbsp;</p>
						<p>Best regards,</p>
						<p>EMA Asthetics</p>'
   		]);

   		EmailTemplates::firstOrCreate([
	       'name' => 'Delete User',
	       'slug' =>'delete_user',
	       'subject'=>'EMA Asthetics - Account Delete',
	       'content'=>'<p>Dear {{name}},</p>
						<p>&nbsp;</p>
						<p>Your account has been deleted by the Administrator.</p>
						<p>&nbsp;</p>
						<p>Best regards,</p>
						<p>EMA Asthetics</p>'
   		]);

   		EmailTemplates::firstOrCreate([
	       'name' => 'Suspend User Account',
	       'slug' =>'suspend_user_account',
	       'subject'=>'EMA Asthetics - Account Suspended',
	       'content'=>'<p>Dear {{name}},</p>
						<p>&nbsp;</p>
						<p>Your account has been suspended. Please contact to the Administrator.</p>
						<p>&nbsp;</p>
						<p>Best regards,</p>
						<p>EMA Asthetics</p>'
   		]);

   		EmailTemplates::firstOrCreate([
	       'name' => 'Release User Account',
	       'slug' =>'release_user_account',
	       'subject'=>'EMA Asthetics - Release Account',
	       'content'=>'<p>Dear {{name}},</p>
						<p>&nbsp;</p>
						<p>Your account has been active. Please do login with your username/password or you can reset your password.</p>
						<p>&nbsp;</p>
						<p>Best regards,</p>
						<p>EMA Asthetics</p>'
   		]);
    }
}
