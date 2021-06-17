<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DistributorUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\User;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Auth;
use App\TreatmentCenter;
use Mail;
use App\Distributor;
use App\Mail\SendDynamicEmail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DistributorUsersController extends Controller
{
	//
	public $successStatus=200;

		/**
		*	Add distributor  user principal/sales
		*   @return json
		*/
		public function addDistributorUser(Request $request){

			$requestData = $request->all();
			$input = $requestData['formData'];

			$validator=Validator::make($input,[
				'name' => ['required', 'string', 'max:255'],
				'email' => 'required|string|unique:users,email,NULL,id,deleted_at,NULL',
				'username' => 'required|string|unique:users,username,NULL,id,deleted_at,NULL',
				'role' => ['required'],
				'primary_telephone_number'=>['required'],
				'mobile_telephone_number'=>['required'],
				]);

				if($validator->fails()){
					return response()->json(['error'=>$validator->errors()],422);
				}else{

					/*Generate the internal-id */
					$getData=getTotalRecords('users');
					$getgenerateInternalId=generateInternalId($getData['incremented_record']);
					/*Generate the internal-id */

					$password=Str::random(10);
					$input['password']=bcrypt($password);
					$input['internal_id']=$getgenerateInternalId;
					$input['primary_telephone_number'] =  addSymbol($input['primary_telephone_number']);
					$input['mobile_telephone_number'] = addSymbol($input['mobile_telephone_number']);

					/* Get Role name by role name*/
						$role = Role::find($input['role']);
					/* Get Role name by role name*/

					$user=User::create($input);
					$user->assignRole($input['role']);
					$user['password']=$password;
					$accessToken =  $user->createToken('authToken')->accessToken;
					$username=$user->username;

					/*assign distributor  to user*/
					$assignDistributoruser=new DistributorUser;
					$assignDistributoruser->fk_user_id = $user->id;
					$assignDistributoruser->fk_role_id = $input['role'];
					$assignDistributoruser->fk_distributor_id = $input['fk_distributor_id'];
					$assignDistributoruser->save();
					/*assign distributor  to user*/


					/*Add action in audit log*/
					$getDistributorCompanyData = getDistributorCompanyDataById($user->id);
					$companyName = ucfirst($getDistributorCompanyData->full_company_name);
					$moduleName = 'user';
					$moduleActivity = 'Add Distributor user';
					$usercompanyName = getUserCompanyName(Auth::user());
					// $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has added ' .ucfirst($input['name']) ." Distributor user"." (".getUserRoles($role->name).").";
					$description =  "New user ".ucfirst($input['name'])." (".getUserRoles($role->name).") for Distributor ".$companyName." has been added";
					captureAuditLog($moduleName,$moduleActivity,$description,$input,$usercompanyName);
					/*Add action in audit log*/


					$data['slug'] = 'welcome_email';
					$data['name'] = $user->name;
					$data['username'] = $user->username;
					$data['new_password'] =  $password;

					Mail::to($user->email)->queue(new SendDynamicEmail($data));

					$moduleName = 'Email';
					$moduleActivity = 'Email logged for Added user';
					$description = 'Email has been sent to Added user: '.$user->name;
					$requestData = array('user_id'=>$user->id);

					/*Add action in audit log*/
						captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
					/*Add action in audit log*/

					// return response
					$response=[
						'success'=>true,
						'message'=>'User registration successfully',
						'accessToken'=>$accessToken,
						'username'=>$username,
						'last_inserted_id'=>$user->id,
					];

					if($request->wantsJson()){
						return response()->json($response,$this->successStatus);
					}
				}
		}

		/**
		* list the distributor users
		* @return json
		*/
		public function listDistributorUser(Request $request){
			if($request->ajax()){
				if($request->role_id){
					$roleId=array($request->role_id);
				}else{
					$searchData = $request->search_data;
					$roleId=array(7,8,9);
					if(!empty($searchData)){
						$getUsersList=DistributorUser::getUsersList($roleId,$request->distributor_id,$searchData);
						 if(!empty($getUsersList)){
								foreach($getUsersList as $key=>$getUsersListRow){

										if($getUsersListRow['new_status']==3 ){
												$getUsersListRow['new_status'] = 'Suspended';
										}else{
												$getUsersListRow['new_status'] = 'Active';
										}
										if(!empty($searchData)){
												if($getUsersListRow['online']==1 ){
														$getUsersListRow['online'] = 'Yes';
												}else{
														$getUsersListRow['online'] = 'No';
												}
										}
										$getUsersListRow['roles_name'] = getUserRoles($getUsersListRow['roles_name']);
										$getUsersList[$key]  = $getUsersListRow;
								}
        		}
						$data = makeNumericArray($getUsersList);
						return $data;
					}else{
						$getUsersList=DistributorUser::getUsersList($roleId,$request->distributor_id);
						return Datatables::of($getUsersList)
						->addColumn('status',function($user){
							if($user->status==0){
								return 'In Active';
							}elseif($user->status==1){
								return 'Active';
							}elseif($user->status==2){
								return 'Deleted';
							}elseif($user->status==3){
								return 'Suspended';
							}
						})

						->editColumn('created_at', function ($getUsersList){
							return date('d-m-Y', strtotime($getUsersList->created_at) );
						})

						->editColumn('roles_name', function ($getUsersList){
							return $getUsersList->roles_name = getUserRoles($getUsersList->roles_name);
						})
						->filterColumn('roles_name', function($query, $keyword) {
							$query->where('roles.name','LIKE',"%".$keyword."%");
						})
						->filterColumn('name', function($query, $keyword) {
							$query->where('users.name','LIKE',"%".$keyword."%");
						})

						->addColumn('online',function($user){
							$green= asset("images/green_dot.png");
							$red= asset("images/red_dot.png");
							return $user->is_logged_in != 0 ? '<img src='.$green.' border="0" width="15px" style="text-align: center;" class="img-rounded" align="center" />' : '<img src='.$red.' border="0" width="15px" style="text-align: center;" class="img-rounded" align="center" />';
						})

						->addColumn('action', function ($user) {
							$edit = $view = $changePassword = $delete = $suspend = $forcelogout='';
							$authUser = Auth::user();

							$distributor_id = $user->fk_distributor_id;
							$distributor=Distributor::find($distributor_id);

							if($authUser->hasRole(['system administrator','distributor principal'])){
								$edit = '<a href="javascript:;" class="editDistributorUser" data-UserId="'.$user->id.'" data-target="#edit_user" title="Edit User" data-toggle="tooltip"><i class="far fa-edit"></i></a>';
							}
							if($authUser->hasRole(['system administrator','distributor principal'])){
								$delete = '<a href="javascript:;" data-UserId="'.$user->id.'" class="delete-user" data-UserName="'.ucfirst($user->name).'" title="Delete User" data-toggle="tooltip"><i class="far fa-trash-alt"></i></a>';
							}

						if($distributor->status!=3){
							if($authUser->hasRole(['system administrator','distributor principal','ema service support'])){
									$suspend = $user->status == 3 ?
									 '<a href="javascript:;" data-UserId="'.$user->id.'" data-UserNameRelease="'.ucfirst($user->name).'" class="releaseuser" title="Release User" data-toggle="tooltip"><i class="fas fa-unlock"></i></a>' :
									'<a href="javascript:;" data-UserId="'.$user->id.'" data-UserName="'.ucfirst($user->name).'" class="suspenduser" title="Suspend User" data-toggle="tooltip"><i class="fas fa-user-lock"></i></a>';
							}
						}
								if($authUser->hasRole(['system administrator','ema service support','distributor principal'])){
									$changePassword = '<a href="javascript:;" data-UserId="'.$user->id.'"  data-UserName="'.ucfirst($user->name).'" class="change-password"  title="Reset Password User" data-toggle="tooltip"><i class="fa fa-key"></i></a>';
									$forcelogout = $user->is_logged_in != 0 ? '<a href="javascript:;" data-UserId="'.$user->id.'" data-UserName="'.ucfirst($user->name).'" class="forcelogout" title="Force Logout User"><i class="fas fa-sign-out-alt"></i></a>' : '';
								}


							if($authUser->hasRole(['system administrator','ema analyst','distributor principal','distributor sales','distributor service','ema service support'])){
								$view = '<a href="javascript:;" class="viewDetails" data-UserId='.$user->id.' title="View User" data-toggle="tooltip"><i class="far fa-eye"></i></a>';
							}
							return $edit.$view.$suspend.$changePassword.$forcelogout.$delete;

						})->rawColumns(['online','action','role'])->toJson();
					}
				}
			}else{
				return view('distributor.distributor-user');
			}
		}
		/**
		* update the distributor users
		* @return json
		*/
		public function updateDistributorUser(Request $request){
			$input = $request->all();
			$validator = Validator::make($request->all(), [
				'name' => ['required', 'string', 'max:255'],
				'email' => 'required|string|unique:users,email,NULL,id,deleted_at,NULL.$input[user_id]',
				'role' => ['required'],
				'primary_telephone_number'=>['required'],
				'mobile_telephone_number'=>['required'],
				'fk_distributor_id'=>['required'],
				'user_id'=>['required'],
				]);

				if($validator->fails()){
					return response()->json(['error'=>$validator->errors()],401);
				}else{
					$userId=$input['user_id'];
					$user=User::find($userId);

					/* Get original Data  before update*/
					$originalData = getOriginalData($user);
					/* Get original Data  before update*/

					$is_email_change = 0;
					if(isset($user) && !empty($user)){
						$usercompanyName = getUserCompanyName(Auth::user());
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
							$description = 'Email has been sent to user : '.ucfirst($input['name']);
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
						$user->syncRoles([$request->input('role')]);
						$user->primary_telephone_number = addSymbol($input['primary_telephone_number']);
						$user->mobile_telephone_number = addSymbol($input['mobile_telephone_number']);
						$user->save();

						/*delete the previous role and update new */
							$userId = DistributorUser::where('fk_user_id', '=', $userId)->delete();
						/*delete the previous role and update new */

						/*assign distributor to user*/
						$assignDistributoruser = new DistributorUser;
						$assignDistributoruser->fk_user_id = $user->id;
						$assignDistributoruser->fk_role_id = $request->input('role');
						$assignDistributoruser->fk_distributor_id = $input['fk_distributor_id'];
						$assignDistributoruser->timestamps = false;
						$assignDistributoruser->updated_at = Carbon::now();
						$assignDistributoruser->save();
						/*assign distributor to user*/

						/* Get Role name by role name*/
						$role = Role::find($request->input('role'));
						/* Get Role name by role name*/

						/*Add action in audit log*/
						$getDistributorCompanyData = getDistributorCompanyDataById($user->id);
						$companyName = ucfirst($getDistributorCompanyData->full_company_name);
						$moduleName = 'user';
						$moduleActivity = 'Updated Distributor user';
						// $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has updated ' .ucfirst($input['name']) ." Distributor user"." (".getUserRoles($role->name).").";//;
						$description = "Distributor  ".ucfirst($input['name'])." (".getUserRoles($role->name).") has been amended for ".$companyName;
						$requestData = $request->all();

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
						$moduleActivity = 'Email logged for Updated user';
						$description = 'An update user email has been sent to '.ucfirst($user->name);
						$requestData = array('user_id'=>$user->id);

					/*Add action in audit log*/
						captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
					/*Add action in audit log*/

						// return response
						$response = [
							'success' => true,
							'message' => 'User record updated successfully',
							'last_updated_id'=>$user->id,
						];

						if($request->wantsJson()){
							return response()->json($response, $this->successStatus);
						}else{
							/*return redirect()->route('home')
							->with('success','User created successfully.');*/
							return response()->json($response, $this->successStatus);
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

		public function uniqueUserEmail(Request $request){
			if($request->ajax()){
				if($request->id){
					$distributor=User::where('email',$request->email)
					->where('id','<>',$request->id)
					->where('status','!=',2)
					->where('deleted_at','=',NULL)
					->first();
				}else{
					$distributor=User::where('email',$request->email)
					->where('status','!=',2)
					->where('deleted_at','=',NULL)
					->first();
				}

				if($distributor){
					return response()->json(false);
				}else{
					return response()->json(true);
				}
			}
		}

		public function uniqueUserName(Request $request){
			if($request->id){
				$distributor=User::where('username',$request->username)
				->where('id','<>',$request->id)
				->where('status','!=',2)
				->first();
			}else{
				$distributor=User::where('username',$request->username)
				->where('status','!=',2)
				->where('deleted_at','=',NULL)
				->first();
			}

			if($distributor){
				return response()->json(false);
			}
			else{
				return response()->json(true);
			}
		}

		public function show($id){
			$user = User::where('id',$id)->with('roles')->first();

			if($user){
				return response()->json(['success'=>true,'data'=>$user,'message'=>'User found.']);
			}
			else{
				return response()->json(['success'=>false,'message'=>'User does not exist.']);
			}
		}

		public function getDistributorUser(Request $request){
			$user = User::where('id',$request->id)->with('roles')->first();
			//dd($user);
			if($user){
				return response()->json(['success'=>'1','data'=>$user,'message'=>'User found.']);
			}
			else{
				return response()->json(['success'=>false,'message'=>'User does not exist.']);
			}
		}

		public function treatmentCenter(Request $request){
			//list of treatment center ///

			$searchData=$request->search_data;
			if(!empty($searchData)){
				$distributor=TreatmentCenter::getTreatmentList($request->distributor_id,$searchData);
				$data = makeNumericArray($distributor);
				return $data;
			}else{
				$regionSearch = $request->get('columns')['2']['search']['value'];
				$totalInstalledDevice = $request->get('columns')['6']['search']['value'];
				$data = TreatmentCenter::getTreatmentList($request->distributor_id);

				if(!empty($regionSearch)){
					$data->havingRaw('LOWER(primary_region) LIKE ?', ["%{$regionSearch}%"]);
				}

				if(!empty($totalInstalledDevice)){
					$data->having('install_device',$totalInstalledDevice);
				}

				return Datatables::of($data)
				->addColumn('action',function($treatment){
					return '<a href="javascript:;" class="viewtreatmentcenterDetails" data-treatmentCenterId="'.$treatment->id.'"><i class="far fa-eye"></i></a>';
				})

				->filterColumn('primary_region', function($query, $keyword) {
					$query->havingRaw('LOWER(primary_region) LIKE ?', ["%{$keyword}%"]);
				})
				->filterColumn('install_device', function($query, $keyword) {
					$query->having('LOWER(install_device) LIKE ?', ["%{$keyword}%"]);
				})
				->toJson();

			}

		}

		public function treatementcentreData(Request $request){
			$treatmentCentreData=TreatmentCenter::select('d.full_company_name as distributor_name','treatment_center.*')
																					->leftjoin('distributors as d','d.id','=','treatment_center.distributors')
																					->where('treatment_center.id',$request->id)->with('country')
																					->first();
			if($treatmentCentreData){
				return response()->json(['success'=>true,'data'=>$treatmentCentreData,'message'=>'details found.']);
			}
			else{
				return response()->json(['success'=>false,'message'=>'User does not exist.']);
			}
		}

}
