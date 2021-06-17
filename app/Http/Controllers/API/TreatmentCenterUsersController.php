<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TreatmentCenterUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\User;
use Auth;
use App\Mail\SendDynamicEmail;
use Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class TreatmentCenterUsersController extends Controller
{
	public $successStatus = 200;

	/**
	*	Add treatment center user principal/sales
	*   @return json
	*/
	public function addTeatmentCenterUser(Request $request){
		$requestData = $request->all();
		$input = $requestData['formData'];
		$validator = Validator::make($input, [
			'name' => ['required', 'string', 'max:255'],
			'email' => 'required|string|unique:users,email,NULL,id,deleted_at,NULL',
			'username' => 'required|string|unique:users,username,NULL,id,deleted_at,NULL',
			'primary_telephone_number'=>['required'],
			'mobile_telephone_number'=>['required']
		]);

			if ($validator->fails()) {
				if($request->wantsJson()){
					return response()->json(['errors'=>$validator->errors()], 422);
				}
				else{
					return redirect()->back()->with('errors',$validator->errors());
				}
			}

			/*Generate the internal-id */
			$getData = getTotalRecords('users');
			$getgeneratedInternalId  = generateInternalId($getData['incremented_record']);
			/*Generate the internal-id */

			$password = Str::random(10);
			$input['password'] = bcrypt($password);
			$input['internal_id'] = $getgeneratedInternalId;
			$input['primary_telephone_number'] =  addSymbol($input['primary_telephone_number']);
			$input['mobile_telephone_number'] = addSymbol($input['mobile_telephone_number']);

			$user = User::create($input);
			$user->assignRole(5);
			$user['password'] = $password;
			$accessToken =  $user->createToken('authToken')->accessToken;
			$username =  $user->username;


			/*assign treatment center to user*/
			$assignTreatmentCenteruser = new TreatmentCenterUser;
			$assignTreatmentCenteruser->fk_user_id = $user->id;
			$assignTreatmentCenteruser->fk_role_id = 5;
			$assignTreatmentCenteruser->fk_treatment_center_id = $input['treatment_center_id'];
			$assignTreatmentCenteruser->save();
			/*assign treatment center to user*/

			/* Get Role name by role name*/
					$role = Role::find(5);
			/* Get Role name by role name*/

			/*Add action in audit log*/
			$getTreatmentCentreData = getGetTreatmentCentreCompanyDataByUserId($user->id);
			$companyName = ucfirst($getTreatmentCentreData->full_company_name);
			$moduleName = 'user';
			$moduleActivity = 'Added Treatment Centre user';
			// $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has added ' .ucfirst($input['name']) ." treatment centre user"." (".getUserRoles($role->name).").";
			$description="New user ".ucfirst($input['name'])." (".getUserRoles($role->name).") for Treatment Centre ".$companyName." has been added ";
			$requestData = $input;
			$usercompanyName = getUserCompanyName(Auth::user());
			captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
			/*Add action in audit log*/

			$data['slug'] = 'welcome_email';
			$data['name'] = $user->name;
			$data['username'] = $user->username;
			$data['new_password'] =  $password;

			Mail::to($user->email)->queue(new SendDynamicEmail($data));

			$moduleName = 'Email';
			$moduleActivity='Email logged for User added';
			$description = 'Email has been sent to added user: '.ucfirst($user->name);
			$requestData = $request->all();

			/*Add action in audit log*/
			captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
			/*Add action in audit log*/

			// return response
			$response = [
				'success' => 'true',
				'message' => 'User created successfully',
				'accessToken' => $accessToken,
				'username' => $username
			];

			if($request->wantsJson()){
				return response()->json($response, $this->successStatus);
			}else{
				return redirect()->route('home')
				->with('success','User created successfully.');
			}
		}

		/**
		* list the trearment center users
		* @return json
		*/
		public function listTeatmentCenterUser(Request $request){
			$roleId = 5;
			$getUserId = $request->input('principal_id');
			$getUsersList = TreatmentCenterUser::getUsersList($roleId,$getUserId);

			if (!empty($getUsersList)) {
				$response = [
					'success' => 'success',
					'message' => 'users list found',
					'users_list'=>$getUsersList
				];
			}else{
				$response = [
					'success' => 'success',
					'message' => 'no data available'
				];
			}

			return response()->json($response, $this->successStatus);
		}


		/**
		* update the trearment center users
		* @return json
		*/
		public function updateTeatmentCenterUser(Request $request){
			$requestData = $request->all();
			$input = $requestData['formData'];
			$validator = Validator::make($input, [
				'name' => ['required', 'string', 'max:255'],
				'email' =>'required|string|unique:users,email,NULL,id,deleted_at,NULL.$input[user_id]',
				'primary_telephone_number'=>['required'],
				'mobile_telephone_number'=>['required'],
				'treatment_center_id'=>['required'],
				'user_id'=>['required'],
				]);
				if ($validator->fails()) {
					if($request->wantsJson()){
						return response()->json(['errors'=>$validator->errors()], 422);
					}else{
						return redirect()->back()->with('errors',$validator->errors());
					}
				}

				$userId = $input['user_id'];
				$user = User::find($userId);
				$is_email_change = 0;
				if(isset($user) && !empty($user)){
					$usercompanyName = getUserCompanyName(Auth::user());
					/* Get original Data  before update*/
					$originalData = getOriginalData($user);
					/* Get original Data  before update*/

					if($user->email != $input['email'] ){
						$data = array();
						$data['slug'] = 'email_change_on_profile_update';
						$data['name'] = $input['name'];
						$data['email'] = $input['email'];
						$data['old_email'] = $user->email;
						$data['username'] =$user->username;
						$data['primary_telephone_number'] = $input['primary_telephone_number'];
						$data['mobile_telephone_number'] = $input['mobile_telephone_number'];
						Mail::to($user->email)->queue(new SendDynamicEmail($data));
						$is_email_change = 1;

						$moduleName='Email';
						$moduleActivity='Email logged for user has changed email';
						$description = 'Email has been sent to user: '.ucfirst($input['name']);
						$requestData = array('user_id'=>$userId);

						captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$usercompanyName);
					}

					if($is_email_change == 1){
						/* AT-2049 - Send SMS */
						$template_slug = 'email_update';
						$recipient = $input['mobile_telephone_number'];
						SendTwilioSMS($recipient, $template_slug);
					}else{
						/* AT-2049 - Send SMS */
						$template_slug = 'user_details_update';
						$recipient = $user->mobile_telephone_number;
						SendTwilioSMS($recipient, $template_slug);
					}

					$user->name = $input['name'];
					$user->email = $input['email'];
					$user->assignRole(5);
					$user->primary_telephone_number = addSymbol($input['primary_telephone_number']);
					$user->mobile_telephone_number = addSymbol($input['mobile_telephone_number']);
					$user->save();

					/*delete the previous role and update new */
					$userId = TreatmentCenterUser::where('fk_user_id', '=', $userId)->delete();
					/*delete the previous role and update new */

					/*assign treatment center to user*/
					$assignTreatmentCenteruser = new TreatmentCenterUser;
					$assignTreatmentCenteruser->fk_user_id = $user->id;
					$assignTreatmentCenteruser->fk_role_id = 5;
					$assignTreatmentCenteruser->fk_treatment_center_id = $input['treatment_center_id'];
					$assignTreatmentCenteruser->save();
					/*assign treatment center to user*/

					/* Get Role name by role name*/
					$role = Role::find(5);
					/* Get Role name by role name*/

					/*Add action in audit log*/
					$getTreatmentCentreData = getGetTreatmentCentreCompanyDataByUserId($user->id);
					$companyName = ucfirst($getTreatmentCentreData->full_company_name);
					$moduleName = 'user';
					$moduleActivity = 'Updated Treatment Centre user';
					//$description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has updated ' .ucfirst($input['name']) ." treatment center user"." (".getUserRoles($role->name)." ).";
					$description="Treatment Centre ".ucfirst($input['name'])." (".getUserRoles($role->name).") has been amended for ".$companyName;
					captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$usercompanyName);
					/*Add action in audit log*/

					$data['slug'] = 'user_update';
					$data['name'] = $user->name;
					$data['username'] = $user->username;
					$data['primary_telephone_number'] = $user->primary_telephone_number;
					$data['mobile_telephone_number'] = $user->mobile_telephone_number;
					$data['email'] = $user->email;
					Mail::to($user->email)->queue(new SendDynamicEmail($data));


					$moduleName = 'Email';
					$moduleActivity='Email logged for User updated';
					// $description = 'Email has been sent to updated user : '.ucfirst($user->name);
					$description ="An update user email has been sent to ".ucfirst($user->name);
					$requestData = array('user_id'=>$user->id);

					/*Add action in audit log*/
					captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
					/*Add action in audit log*/

					// return response
					$response = [
						'success' => 'true',
						'message' => 'User record updated successfully'
					];

					if($request->wantsJson()){
						return response()->json($response, $this->successStatus);
					}else{
						return redirect()->route('home')
						->with('success','Principal updated successful');
					}
				}else{
					$message = 'user not found';
					$response = [
						'success' => 'fail',
						'message' => $message,
					];
					return response()->json($response, 404);
				}
			}
		}
