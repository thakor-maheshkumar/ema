<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\TreatmentCenter;
use App\Distributor;
use App\Country;
use App\HydraCoolSrp;
use Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\DynamoDbModel;
use App\TreatmentJson;
use App\TreatmentCentreFile;
use BaoPham\DynamoDb\Facades\DynamoDb;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDynamicEmail;
use \Illuminate\Support\Facades\URL;
use DateTime;
use App\TreatmentCentrePAR;
use App\TreatmentCentreWAE;
use App\TreatmentCentreCSN;
use App\TreatmentJsonParent;
use App\ErrorCode;



class TreatmentCenterController extends Controller
{

	public $successStatus = 200;

	/**
	* load the treatment center view
	*/
	public function treatmentcenterList(Request $request){
		if($request->ajax()){
			$searchData =$request->get('search_data');
			if(isset($searchData) && !empty($searchData)){
				$gettreatmentcentredata = TreatmentCenter::getTreatmentCenterList(null,$searchData);
				$data = makeNumericArray($gettreatmentcentredata);
				return $data;
			}else{
				$getTreatmentCenterList = TreatmentCenter::getTreatmentCenterList();
				$regionSearch = $request->get('columns')['2']['search']['value'];
				$installDevice = $request->get('columns')['6']['search']['value'];
				if(!empty($regionSearch)){
					$getTreatmentCenterList->havingRaw('LOWER(primary_region) LIKE ?', ["%{$regionSearch}%"]);
				}

				if(!empty($installDevice)){
					$getTreatmentCenterList->having('install_device',$installDevice);
				}


				return Datatables::of($getTreatmentCenterList)
				->addColumn('action', function ($getTreatmentCenterRow) {
					$edit = $view = $changePassword = $list = $delete = $suspend = '';
					$authUser = Auth::user();
					$getAssociatedData = TreatmentCenter::getTreatmentCenterAssociatedActiveCount($getTreatmentCenterRow->id);

					if($getTreatmentCenterRow->status=="3"){
						$icon = "fas fa-unlock";
						$class = "releasetmentcenter";
						$title="Release Treatment Centre";
					}else{
						$icon = "fas fa-user-lock";
						$class = "suspendtreatmentcenter";
						$title="Suspend Treatment Centre";
					}

					if($authUser->hasRole(['system administrator','distributor principal','distributor service','distributor sales'])){
						$edit = '<a href="javascript:;" class="editTreatmentCenter" title="Edit Treatment Centre" data-treatmentCenterId='.$getTreatmentCenterRow->id.'><i class="far fa-edit"></i></a>';
					}

					if($authUser->hasRole(['system administrator'])){
						$suspend = '<a href="javascript:;" class="'.$class.'" data-treatmentcenterId='.$getTreatmentCenterRow->id.' data-treatmentcenterName="'.ucfirst($getTreatmentCenterRow->full_company_name).'" title="'.$title.'" ><i class="'.$icon.'"></i></a>';
					}

					if($authUser->hasRole(['system administrator','ema analyst','ema service support','distributor principal','distributor service','distributor sales'])){
						$view = '<a href='.route('view-treatment-center',['id'=>$getTreatmentCenterRow->id]).' title="View Treatment Centre"><i class="far fa-eye"></i></a>';
					}

					//if($getAssociatedData[0] == 0 && $getAssociatedData[1] == 0 && $getAssociatedData[2] == 0){
						if($getAssociatedData[0] == 0 && $getAssociatedData[1] == 0){
							if($authUser->hasRole(['system administrator'])){
								$delete='<a href="javascript:;" class="deleTetreatmentCenter" data-treatmentCenterId='.$getTreatmentCenterRow->id.' title="Delete Treatment Center" data-treatmentCenterName="'.ucfirst($getTreatmentCenterRow->full_company_name).'"><i class="far fa-trash-alt"></i></a>';
							}
						}

						return $edit.$view.$list.$suspend.$delete;
					})
					->editColumn('status', function($getTreatmentCenterList) {
						if($getTreatmentCenterList->status==3){
							$status = 'Suspended';
						}else{
							$status = 'Active';
						}
						return $getTreatmentCenterList->status = $status;
					})
					->filterColumn('primary_region', function($query, $keyword) {
						$query->havingRaw('LOWER(primary_region) LIKE ?', ["%{$keyword}%"]);
					})
					->filterColumn('install_device', function($query, $keyword) {
						$query->havingRaw('LOWER(install_device) LIKE ?', ["%{$keyword}%"]);
					})
					->make(true);
				}
			}else{
				$getDistributorData = array();
				$getHydraCoolSrpData = array();

				/* Get all active distributon */
				$getDistributorObj = Distributor::where('status',1)->get();
				if(!empty($getDistributorObj)){
					$getDistributorData = $getDistributorObj;
				}
				/* Get all active distributon */

				/* Get country data */
				$getCountryData = Country::orderBy('name')->get();
				/* Get country data */

				/*Get hydracool srp serial number*/
				$getHydraCoolSrpObj = HydraCoolSrp::getHydraCoolSrpSerialNumber();
				if(!empty($getHydraCoolSrpObj)){
					$getHydraCoolSrpData = $getHydraCoolSrpObj;
				}
				/*Get hydracool srp serial number*/
				return view('treatmentcenter.treatment-centre-list',compact(['getDistributorData','getCountryData','getHydraCoolSrpData']));
			}
		}

		/**
		*	Add treatment center process
		*   @return boolean success/error
		*/
		public function addTeatmentCenter(Request $request){
			$userInput = array();
			$requestData = $request->all();

			$input = $requestData['formData'];

			$validator = Validator::make($input, [
				'full_company_name' => ['required','max:255'],
				'abbreviated_company_name' =>['max:255'],
				'group_name' =>['max:255'],
				'full_address' =>['max:255'],
				'name_of_primary_contact' =>['required','string','max:100'],
				'telephone_number_of_primary_contact' =>['required'],
				'mobile_number_of_primary_contact' =>['required'],
				'email_of_primary_contact' =>['required','max:255'],
				'treatment_ema_code' =>['required'],
				'building_name' =>['max:255'],
				'address_1' =>['required','max:255'],
				'address_2' =>['max:255'],
				'address_3' =>['max:255'],
				'state' =>['required'],
				'zipcode' =>['required'],
				'position'=>['required'],
				'country_id'=>['required']
				]);

				if ($validator->fails()) {
					return response()->json(['errors'=>$validator->errors()], 422);
				}else{

					$getData = getTotalRecords('treatment_center');
					$getgeneratedInternalId  = generateInternalId($getData['incremented_record']);


					/*Add the tratment center*/
					$treatmentcenter = new TreatmentCenter;
					$treatmentcenter->full_company_name=$input['full_company_name'];
					$treatmentcenter->internal_id=$getgeneratedInternalId;
					$treatmentcenter->abbreviated_company_name=(isset($input['abbreviated_company_name']) ? $input['abbreviated_company_name'] : '');
					$treatmentcenter->group_name=(isset($input['group_name']) ? $input['group_name'] : '');
					$treatmentcenter->full_address=(isset($input['full_address']) ? $input['full_address'] : '');
					$treatmentcenter->fax_number=(isset($input['fax_number']) ? addSymbol($input['fax_number']) : '');
					$treatmentcenter->web_site=(isset($input['web_site']) ? $input['web_site'] : '');
					$treatmentcenter->email_of_primary_contact=$input['email_of_primary_contact'];
					$treatmentcenter->name_of_primary_contact=$input['name_of_primary_contact'];
					$treatmentcenter->telephone_number_of_primary_contact=addSymbol($input['telephone_number_of_primary_contact']);
					$treatmentcenter->distributors=$input['distributors'];
					$treatmentcenter->created_by=Auth::user()->id;
					$treatmentcenter->ip_address=request()->ip();
					$treatmentcenter->status=1;
					$treatmentcenter->treatment_ema_code= $input['treatment_ema_code'];
					$treatmentcenter->building_name= (isset($input['building_name']) ? $input['building_name'] : '');
					$treatmentcenter->address_1= $input['address_1'];
					$treatmentcenter->address_2= (isset($input['address_2']) ? $input['address_2'] : '');
					$treatmentcenter->address_3= (isset($input['address_3']) ? $input['address_3'] : '');
					$treatmentcenter->state= $input['state'];
					$treatmentcenter->zipcode= $input['zipcode'];
					$treatmentcenter->position= $input['position'];
					$treatmentcenter->country_id= $input['country_id'];
					$treatmentcenter->mobile_number_of_primary_contact= addSymbol($input['mobile_number_of_primary_contact']);

					if(isset($input['is_ema'])){
						$treatmentcenter->is_ema= 1;
					}else{
						$treatmentcenter->is_ema= 0;
					}
					$treatmentcenter->save();

					if($treatmentcenter){

						/*Add action in audit log*/
						$companyName = ucfirst($input['full_company_name']);
						$moduleName = 'treatment centre';
						$moduleActivity = 'Added Treatment Centre';
						$usercompanyName = getUserCompanyName(Auth::user());
						// $description=ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has added ' .$companyName ." Treatment centre.";
						$description="Treatment Centre ".$companyName." has been added";
						captureAuditLog($moduleName,$moduleActivity,$description,$input,$usercompanyName);
						/*Add action in audit log*/

						$verification_url = URL::temporarySignedRoute('verifyTreatmentEmail', now()->addDay(), ['email' => $input['email_of_primary_contact']]);

						$data_new = array();
						$data_new['slug'] = 'treatment_center_setup';
						$data_new['confirmation_link'] = $verification_url;
						$data_new['name_of_primary_contact'] = ucfirst($input['name_of_primary_contact']);
						$data_new['companyName'] = $companyName;
						Mail::to($treatmentcenter->email_of_primary_contact)->queue(new SendDynamicEmail($data_new));

						$moduleName = 'Email';
						$moduleActivity = 'Email logged for Treatment centre Added';
						$description = 'Email has been sent to Treatment centre primary user: '.ucfirst($treatmentcenter->name_of_primary_contact);
						$requestData = array('treatmentcentre_id'=>$treatmentcenter->id);

						/*Add action in audit log*/
						captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
						/*Add action in audit log*/

						$message = 'Treatment Centre added successfully';
						$treatmentCenterId = $treatmentcenter->id;
						$response = [
							'success' => 'true',
							'message' => $message,
							'treatment_center_id'=>$treatmentCenterId,
							'full_company_name'=>$input['full_company_name']
						];
						return response()->json($response, $this->successStatus);
					}else{
						$message = 'Something wrong please try again';
						$response = [
							'success' => 'fail',
							'message' => $message,
						];
						return response()->json($response, 500);
					}
					/*Add the tratment center*/
				}
			}

			/**
			*	update treatment center process
			*   @return boolean success/error
			*/
			public function updateTeatmentCenter(Request $request){
				$userInput = array();
				$requestData = $request->all();

				$input = $requestData['formData'];
				$treatmentCenterId = 	$input['treatmentCenterId'];
				$validator = Validator::make($input, [
					'full_company_name' => ['required','max:255'],
					'abbreviated_company_name' =>['max:255'],
					'group_name' =>['max:255'],
					'full_address' =>['max:255'],
					'name_of_primary_contact' =>['required','string','max:100'],
					'telephone_number_of_primary_contact' =>['required'],
					'mobile_number_of_primary_contact' =>['required'],
					'email_of_primary_contact' =>['required','max:255'],
					'treatment_ema_code'=>['required'],
					'building_name' =>['max:255'],
					'address_1' =>['required'],
					'address_2' =>['max:255'],
					'address_3' =>['max:255'],
					'state' =>['required'],
					'zipcode' =>['required'],
					'position'=>['required'],
					'country_id'=>['required']
					]);

					if ($validator->fails()) {
						return response()->json(['errors'=>$validator->errors()], 422);
					}else{

						$treatmentcenter = TreatmentCenter::find($treatmentCenterId);
						$oldEmail = $treatmentcenter->email_of_primary_contact;
						$usercompanyName = getUserCompanyName(Auth::user());

						/* Get original Data  before update*/
						$originalData = getOriginalData($treatmentcenter);
						/* Get original Data  before update*/

						$treatmentcenter->full_company_name=$input['full_company_name'];
						$treatmentcenter->abbreviated_company_name=(isset($input['abbreviated_company_name']) ? $input['abbreviated_company_name'] : '');
						$treatmentcenter->group_name=(isset($input['group_name']) ? $input['group_name'] : '');
						$treatmentcenter->full_address=(isset($input['full_address']) ? $input['full_address'] : '');
						$treatmentcenter->fax_number=(isset($input['fax_number']) ? addSymbol($input['fax_number']) : '');
						$treatmentcenter->web_site=(isset($input['web_site']) ? $input['web_site'] : '');
						$treatmentcenter->email_of_primary_contact=$input['email_of_primary_contact'];
						$treatmentcenter->name_of_primary_contact=$input['name_of_primary_contact'];
						$treatmentcenter->telephone_number_of_primary_contact= addSymbol($input['telephone_number_of_primary_contact']);
						$treatmentcenter->distributors=$input['distributors'];
						$treatmentcenter->created_by=Auth::user()->id;
						$treatmentcenter->ip_address=request()->ip();
						$treatmentcenter->treatment_ema_code= $input['treatment_ema_code'];
						$treatmentcenter->building_name= (isset($input['building_name']) ? $input['building_name'] : '');
						$treatmentcenter->address_1= $input['address_1'];
						$treatmentcenter->address_2= (isset($input['address_2']) ? $input['address_2'] : '');
						$treatmentcenter->address_3= (isset($input['address_3']) ? $input['address_3'] : '');
						$treatmentcenter->state= $input['state'];
						$treatmentcenter->zipcode= $input['zipcode'];
						$treatmentcenter->position= $input['position'];
						$treatmentcenter->country_id= $input['country_id'];
						$treatmentcenter->mobile_number_of_primary_contact= addSymbol($input['mobile_number_of_primary_contact']);
						if($input['is_ema']==1){
							$treatmentcenter->is_ema= 1;
						}else{
							$treatmentcenter->is_ema= 0;
						}
						$treatmentcenter->save();

						if($treatmentcenter){

							/*Add action in audit log*/
							$companyName = ucfirst($treatmentcenter->full_company_name);
							$moduleName = 'treatment centre';
							$moduleActivity = 'Updated Treatment Centre';
							// $description=ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has updated ' .$companyName ." Treatment centre.";
							$description="Treatment Centre ".$companyName." has been updated";

							captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$usercompanyName);
							/*Add action in audit log*/

							$data['slug'] = 'treatment_center_update';
							$data['full_company_name']=(isset($input['full_company_name']) ? $input['full_company_name'] : '');
							$data['abbreviated_company_name']=(isset($input['abbreviated_company_name']) ? $input['abbreviated_company_name'] : '');
							$data['group_name']=(isset($input['group_name']) ? $input['group_name'] : '');
							$data['full_address']=(isset($input['full_address']) ? $input['full_address'] : '');
							$data['fax_number']=(isset($input['fax_number']) ? $input['fax_number'] : '');
							$data['web_site']=(isset($input['web_site']) ? $input['web_site'] : '');
							$data['email_of_primary_contact']=$input['email_of_primary_contact'];
							$data['name_of_primary_contact']=$input['name_of_primary_contact'];
							$data['telephone_number_of_primary_contact']=$input['telephone_number_of_primary_contact'];
							$data['distributors']=$input['distributors'];
							$data['treatment_ema_code']= $input['treatment_ema_code'];
							$data['building_name']= (isset($input['building_name']) ? $input['building_name'] : '');
							$data['address_1']= $input['address_1'];
							$data['address_2']= (isset($input['address_2']) ? $input['address_2'] : '');
							$data['address_3']= (isset($input['address_3']) ? $input['address_3'] : '');
							$data['state']= $input['state'];
							$data['zipcode']= $input['zipcode'];
							$data['position']= $input['position'];
							$data['country_id']= $input['country_id'];
							$data['mobile_number_of_primary_contact']= $input['mobile_number_of_primary_contact'];
							Mail::to($treatmentcenter->email_of_primary_contact)->queue(new SendDynamicEmail($data));

							$moduleName = 'Email';
							$moduleActivity = 'Email logged for Treatment centre Updated';
							$description = 'Email has been sent to Treatment centre primary user: '.ucfirst($treatmentcenter->name_of_primary_contact);
							$requestData = array('treatmentcentre_id'=>$treatmentcenter->id);

							/*Add action in audit log*/
							captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
							/*Add action in audit log*/


							/* AT-2049 - Send EMAIL if email change */
							$is_email_change = 0;
							if($oldEmail != $input['email_of_primary_contact'] ){
								$data = array();
								$data['slug'] = 'email_change_on_profile_update';
								$data['name'] = $input['full_company_name'];
								$data['email'] = $input['email_of_primary_contact'];
								$data['old_email'] = $oldEmail;
								$data['primary_telephone_number'] = $input['telephone_number_of_primary_contact'];
								$data['mobile_telephone_number'] = $input['mobile_number_of_primary_contact'];
								Mail::to($oldEmail)->queue(new SendDynamicEmail($data));
								$is_email_change = 1;

								$moduleName = 'Email';
								$moduleActivity='Email logged for Treatment centre primary user has changed email';
								$description = 'Email has been sent to Treatment centre primary user : '.ucfirst($input['name_of_primary_contact']);
								$requestData = array('treatmentcentre_id'=>$treatmentcenter->id);

								/*Add action in audit log*/
								captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
								/*Add action in audit log*/
							}

							/* AT-2049 - Send SMS */
							if($is_email_change == 1){
								/* AT-2049 - Send SMS */
								$template_slug = 'email_update';
								$recipient = $input['mobile_number_of_primary_contact'];
								SendTwilioSMS($recipient, $template_slug);
							}else{
								/* AT-2049 - Send SMS */
								$template_slug = 'user_details_update';
								$recipient = $input['mobile_number_of_primary_contact'];
								SendTwilioSMS($recipient, $template_slug);
							}

							$treatmentCenterId = $treatmentcenter->id;
							$message = 'Treatment Centre updated successfully';
							$response = [
								'success' => 'true',
								'message' => $message,
								'treatment_center_id'=>$treatmentCenterId
							];
							return response()->json($response, $this->successStatus);
						}else{
							$message = 'Something wrong please try again';
							$response = [
								'success' => 'fail',
								'message' => $message,
							];
							return response()->json($response, 500);
						}
						/*Add update the tratment center*/
					}
				}

				/**
				*	delete treatment center process
				*   @return boolean success/error
				*/
				public function deleteTeatmentCenter(Request $request){
					$userInput = array();
					$input = $request->all();
					$treatmentCenterId = 	$input['treatment_center_id'];

					$validator = Validator::make($request->all(), [
						'treatment_center_id' =>['required','numeric'],
						]);

						if ($validator->fails()) {
							return response()->json(['error'=>$validator->errors()], 401);
						}else{

							/*delete treatment center */
							$treatmentcenter = TreatmentCenter::find($treatmentCenterId);

							if(isset($treatmentcenter) && !empty($treatmentcenter)){
								$getFullCompanyName = ucfirst($treatmentcenter->full_company_name);

								$treatmentcenter->status=2;
								$treatmentcenter->save();
								if($treatmentcenter){

									/* Delete treatment centre principal */
									TreatmentCenter::updateTreatmentCentrePrincipalStatus($treatmentCenterId,2);
									/* Delete treatment centre principal */

									/*Add action in audit log*/
									$moduleName = 'treatment centre';
									$moduleActivity = 'Deleted Treatment Centre';
									//$description=ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has deleted ' .$getFullCompanyName ." Treatment centre.";
									$description= "Treatment Centre ".$getFullCompanyName." has been deleted";
									$usercompanyName = getUserCompanyName(Auth::user());
									$requestData = array('treatment_centre_id'=>$treatmentCenterId);

									captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
									/*Add action in audit log*/

									$data['slug'] = 'treatment_center_deleted';
									$data['name_of_primary_contact']=$treatmentcenter->name_of_primary_contact;
									$data['treatment_ema_code']= $treatmentcenter->treatment_ema_code;
									$data['full_company_name']= $getFullCompanyName;
									Mail::to($treatmentcenter->email_of_primary_contact)->queue(new SendDynamicEmail($data));

									$moduleName = 'Email';
									$moduleActivity = 'Email logged for Treatment centre Deleted';
									$description = 'Email has been sent to Treatment centre primary user: '.ucfirst($treatmentcenter->name_of_primary_contact);
									$requestData = array('treatmentcentre_id'=>$treatmentcenter->id);

									/*Add action in audit log*/
									captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
									/*Add action in audit log*/

									$message = 'Treatment Centre deleted successfully';
									$response = [
										'success' => 'true',
										'message' => $message,
									];
									return response()->json($response, $this->successStatus);
								}else{
									$message = 'Something wrong please try again';
									$response = [
										'success' => 'fail',
										'message' => $message,
									];
									return response()->json($response, 500);
								}
							}else{
								$message = 'Treatment Center not found';
								$response = [
									'success' => 'fail',
									'message' => $message,
								];
								return response()->json($response, 404);
							}
							/*delete treatment center */
						}

					}

					/**
					*	suspend treatment center process
					*   @return boolean success/error
					*/
					public function suspendTeatmentCenter(Request $request){
						$userInput = array();
						$input = $request->all();
						$treatmentCenterId = 	$input['treatment_center_id'];

						$validator = Validator::make($request->all(), [
							'treatment_center_id' =>['required','numeric'],
							]);

							if ($validator->fails()) {
								return response()->json(['error'=>$validator->errors()], 401);
							}else{

								/*suspend treatment center */
								$treatmentcenter = TreatmentCenter::find($treatmentCenterId);

								if(isset($treatmentcenter) && !empty($treatmentcenter)){
									$getFullCompanyName = ucfirst($treatmentcenter->full_company_name);
									$treatmentcenter->status=3;
									$treatmentcenter->save();
									if($treatmentcenter){

										$moduleName = 'treatment centre';
										$moduleActivity = 'Suspended Treatment Centre';
										// $description=ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has Suspended ' .$getFullCompanyName ." Treatment centre.";
										$description= "Treatment Centre ".$getFullCompanyName." has been suspended";
										$requestData = array('treatment_centre_id'=>$treatmentCenterId);
										$usercompanyName = getUserCompanyName(Auth::user());

										/*Add action in audit log*/
										captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
										/*Add action in audit log*/

										$data['slug'] = 'treatment_center_suspended';
										$data['name_of_primary_contact']=$treatmentcenter->name_of_primary_contact;
										$data['treatment_ema_code']= $treatmentcenter->treatment_ema_code;
										$data['full_company_name']= $getFullCompanyName;
										Mail::to($treatmentcenter->email_of_primary_contact)->queue(new SendDynamicEmail($data));


										$moduleName = 'Email';
										$moduleActivity = 'Email logged for Treatment centre Suspended';
										$description = 'Email has been sent to Treatment centre primary user: '.ucfirst($treatmentcenter->name_of_primary_contact);
										$requestData = array('treatmentcentre_id'=>$treatmentcenter->id);

										/*Add action in audit log*/
										captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
										/*Add action in audit log*/

										$getAllUsers =getAllUsersByTreatmentcentreId(array($treatmentcenter->id));
										if(!empty($getAllUsers)){
											suspendOrReleseCompanyUsers($getAllUsers,'suspend');
										}

										/* Suspend treatment centre principal */
										TreatmentCenter::updateTreatmentCentrePrincipalStatus($treatmentCenterId,3);
										/* Suspend treatment centre principal */

										$message = 'Treatment Centre suspended successfully';
										$response = [
											'success' => 'true',
											'message' => $message,
										];
										return response()->json($response, $this->successStatus);
									}else{
										$message = 'Something wrong please try again';
										$response = [
											'success' => 'fail',
											'message' => $message,
										];
										return response()->json($response, 500);
									}

								}else{
									$message = 'Treatment Centre not found';
									$response = [
										'success' => 'fail',
										'message' => $message,
									];
									return response()->json($response, 404);
								}
								/*suspend treatment center */
							}
						}

						/**
						*	release treatment center process
						*   @return boolean success/error
						*/
						public function releaseTeatmentCenter(Request $request){
							$userInput = array();
							$input = $request->all();
							$treatmentCenterId = 	$input['treatment_center_id'];

							$validator = Validator::make($request->all(), [
								'treatment_center_id' =>['required','numeric'],
								]);


								if ($validator->fails()) {
									return response()->json(['error'=>$validator->errors()], 401);
								}else{

									/*release treatment center */
									$treatmentcenter = TreatmentCenter::find($treatmentCenterId);

									if(isset($treatmentcenter) && !empty($treatmentcenter)){
										$getFullCompanyName = ucfirst($treatmentcenter->full_company_name);
										$treatmentcenter->status=1;
										$treatmentcenter->save();
										if($treatmentcenter){


											$moduleName = 'treatment centre';
											$moduleActivity = 'Released Treatment Centre';
											// $description=ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has Released ' .$getFullCompanyName ." Treatment centre.";
											$description= "Treatment Centre ".$getFullCompanyName." has been released";
											$requestData = array('treatment_centre_id'=>$treatmentCenterId);
											$usercompanyName = getUserCompanyName(Auth::user());

											/*Add action in audit log*/
											captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
											/*Add action in audit log*/

											$data['slug'] = 'treatment_center_released';
											$data['name_of_primary_contact']=$treatmentcenter->name_of_primary_contact;
											$data['treatment_ema_code']= $treatmentcenter->treatment_ema_code;
											$data['full_company_name']= $getFullCompanyName;
											Mail::to($treatmentcenter->email_of_primary_contact)->queue(new SendDynamicEmail($data));

											$moduleName = 'Email';
											$moduleActivity = 'Email logged for Treatment centre Released';
											$description = 'Email has been sent to Treatment centre primary user: '.ucfirst($treatmentcenter->name_of_primary_contact);
											$requestData = array('treatmentcentre_id'=>$treatmentcenter->id);

											/*Add action in audit log*/
											captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
											/*Add action in audit log*/

											$getAllUsers =getAllUsersByTreatmentcentreId(array($treatmentcenter->id));

											if(!empty($getAllUsers)){
												suspendOrReleseCompanyUsers($getAllUsers,'relaese');
											}

											/* release treatment centre principal */
											TreatmentCenter::updateTreatmentCentrePrincipalStatus($treatmentCenterId,1);
											/* release treatment centre principal */


											$message = 'Treatment Centre released successfully';
											$response = [
												'success' => 'true',
												'message' => $message,
											];
											return response()->json($response, $this->successStatus);
										}else{
											$message = 'Something wrong please try again';
											$response = [
												'success' => 'fail',
												'message' => $message,
											];
											return response()->json($response, 500);
										}


									}else{
										$message = 'Treatment Centre not found';
										$response = [
											'success' => 'fail',
											'message' => $message,
										];
										return response()->json($response, 404);
									}
									/*release treatment center */
								}
							}


							/**
							* Check treatment emacode unique
							* @return true/false
							*/
							public function checkTreatmentEmaCodeUnique($emaCode,$exceptId=null){
								if($exceptId){
									$getCount = TreatmentCenter::where('treatment_ema_code',$emaCode)
									->where('id','!=',$exceptId)
									->where('status',1)
									->count();
								}else{
									$getCount = TreatmentCenter::where('treatment_ema_code',$serialNumber)
									->where('status',1)
									->count();
								}

								return $getCount;
							}

							/**
							* check value is exist or not
							* @return true/false
							*/
							public function checkValueIsExists(Request $request){
								$exceptId = $request->input('except_id');
								$field =    $request->input('field');
								$value =    $request->input('value');

								$getCount = TreatmentCenter::where($field,$value)
								->where('status',1);
								if(isset($exceptId) && !empty($exceptId)){
									$getCount->where('id','!=',$exceptId);
								}
								$getCount  = $getCount->count();

								return $getCount;

							}

							/**
							*  Get Treatment center detail
							*  @return obj
							*/
							public function getTreatmentCenterDetails(Request $request){
								$treatmentCenterId= $request->input('treatment_center_id');
								$getTreatmentCenterData = TreatmentCenter::getTreatmentCenterDetail($treatmentCenterId);

								if(isset($getTreatmentCenterData[0]) && !empty($getTreatmentCenterData[0])){
									$message = 'Treatment Centre found';
									$response = [
										'success' => 'true',
										'message' => $message,
										'center_details'=>$getTreatmentCenterData[0]
									];
									return response()->json($response,$this->successStatus);

								}else{
									$message = 'Treatment Centre not found';
									$response = [
										'success' => 'fail',
										'message' => $message,
									];
									return response()->json($response,$this->successStatus);
								}
							}

							/**
							*  Get Treatment assosiated data
							*  @return obj
							*/
							public function viewTreatmentCenterAssosiatedData(Request $request,$id){

								if($request->ajax()){
									$getTreatmentCenterId = $id;
									$searchData =$request->get('search_data');
									if(isset($searchData) && !empty($searchData)){
										$hydracoolData = array();
										$hydracoolSRPData = HydraCoolSrp::getHydraCoolSrpUnits($getTreatmentCenterId,$searchData);
										if(!empty($hydracoolSRPData)){
											foreach($hydracoolSRPData as $key=>$hydracoolSRPRow){
												$hydracoolData[$key]['serial_number'] = $hydracoolSRPRow['serial_number'];
												$hydracoolData[$key]['last_active'] = 0;
												$hydracoolData[$key]['install_device'] = 0;
												$hydracoolData[$key]['last_seven'] = 0;
												$hydracoolData[$key]['last_30_days'] = 0;
												$hydracoolData[$key]['last_90_days'] = 0;
												$hydracoolData[$key]['last_12_month'] = 0;
												$hydracoolData[$key]['total_treatment'] = 0;
											}
										}
										$data = makeNumericArray($hydracoolData);
										return $data;
									}else{

										$checkCentreIsEMA = checkTreatmentCentreISEMA($getTreatmentCenterId);
										$getTreatmentCenterHydracoolSrp = HydraCoolSrp::getHydraCoolSrpUnits($getTreatmentCenterId);

										return Datatables::of($getTreatmentCenterHydracoolSrp)
										->addColumn('action', function ($hydracool_srp) use ($checkCentreIsEMA) {
											$authUser = Auth::user();

											$edit = $view = $delete = '';

											if($checkCentreIsEMA==1){
												if($authUser->hasRole(['system administrator','ema service support'])){
													$delete='<a href="javascript:;" class="deleteHydracoolsrp" title="Delete Device" data-hydracoolsrpId='.$hydracool_srp->id.' data-hydracoolsrpSerialNumber="'.$hydracool_srp->serial_number.'"><i class="far fa-trash-alt"></i></a>';
												}
											}
											if($authUser->hasRole(['system administrator','ema service support','distributor principal'])){
												$edit='<a href="javascript:;" class="edithydracoolsrp" title="Edit Device" data-hydracoolsrpId='.$hydracool_srp->id.'><i class="far fa-edit"></i></a>';
											}
											if($authUser->hasRole(['system administrator','ema analyst','distributor principal','distributor service','distributor sales','treatment centre manager'])){
												$view = '<a href="javascript:;" class="viewHydracoolsrp" title="View Device" data-hydracoolsrpId='.$hydracool_srp->id.'><i class="far fa-eye"></i></a>';
											}

											return $edit.$view.$delete;
										})->make(true);
									}
								}else{
									$getTreatmentCenterId = $id;
									$only_priniple = 0;
									if(!empty($getTreatmentCenterId)){
										$getTreatmentCenterData = TreatmentCenter::getTreatmentCenterDetail($getTreatmentCenterId);
										if(isset($getTreatmentCenterData[0]) && !empty($getTreatmentCenterData[0])){
											$treatmentcenterDetails = $getTreatmentCenterData[0];

											/* Get all active distributon */
											$getDistributorObj = Distributor::where('status',1)->get();
											if(!empty($getDistributorObj)){
												$getDistributorData = $getDistributorObj;
											}
											/* Get all active distributon */

											/* Get country data */
											$getCountryData = Country::orderBy('name')->get();
											/* Get country data */
											$getHydraCoolSrpData = array();

											/*Get hydracool srp serial number*/
											$getHydraCoolSrpObj = HydraCoolSrp::getHydraCoolSrpSerialNumber();
											if(!empty($getHydraCoolSrpObj)){
												$getHydraCoolSrpData = $getHydraCoolSrpObj;
											}

											return view('treatmentcenter.treatment-center-users',compact('treatmentcenterDetails','getTreatmentCenterId','only_priniple','getDistributorData','getCountryData','getHydraCoolSrpData'));
										}else{
											$notification = array(
												'message' => 'No treatment centre found',
												'alert-type' => 'error'
											);
											return redirect('treatment-centre-list')->with($notification);
										}
									}else{
										$notification = array(
											'message' => 'Not allow to view',
											'alert-type' => 'error'
										);
										return redirect('treatment-centre-list')->with($notification);
									}
								}
							}


							/**
							*  Get Treatment assosiated data
							*  @return obj
							*/
							public function getListOfTreatmentCentePrincipal(Request $request,$id){
								$getTreatmentCenterId = $id;
								if($request->ajax()){
									$searchData =$request->get('search_data');
									if(isset($searchData) && !empty($searchData)){
										$getTreatmentCenterPrincipalData = TreatmentCenter::getTreatmentCenterPrincipalList($getTreatmentCenterId,$searchData);
										if(!empty($getTreatmentCenterPrincipalData)){
											foreach($getTreatmentCenterPrincipalData as $key=>$getTreatmentCenterPrincipalRow){

												if($getTreatmentCenterPrincipalRow['status']==3 ){
													$getTreatmentCenterPrincipalRow['status'] = 'Suspended';
												}else{
													$getTreatmentCenterPrincipalRow['status'] = 'Active';
												}
												if(!empty($searchData)){
													if($getTreatmentCenterPrincipalRow['is_logged_in']==1 ){
														$getTreatmentCenterPrincipalRow['is_logged_in'] = 'Yes';
													}else{
														$getTreatmentCenterPrincipalRow['is_logged_in'] = 'No';
													}
												}
												$getTreatmentCenterPrincipalRow['roles_name'] =getUserRoles($getTreatmentCenterPrincipalRow['roles_name']);
												$getTreatmentCenterPrincipalData[$key]  = $getTreatmentCenterPrincipalRow;
											}
										}
										$data = makeNumericArray($getTreatmentCenterPrincipalData);
										return $data;
									}else{
										$gettreatmentCenterPrincipalList = TreatmentCenter::getTreatmentCenterPrincipalList($getTreatmentCenterId);
										return Datatables::of($gettreatmentCenterPrincipalList)

										->editColumn('roles_name', function ($gettreatmentCenterPrincipalList){
											return $gettreatmentCenterPrincipalList->roles_name = getUserRoles($gettreatmentCenterPrincipalList->roles_name);
										})

										->filterColumn('name', function($query, $keyword) {
											$query->where('u.name','LIKE',"%".$keyword."%");
										})
										->editColumn('status', function($gettreatmentCenterPrincipalList) {
											if($gettreatmentCenterPrincipalList->status==3){
												$status = 'Suspended';
											}else{
												$status = 'Active';
											}
											return $gettreatmentCenterPrincipalList->status = $status;
										})
										->addColumn('action', function ($principal_list) {
											$authUser = Auth::user();
											$edit = $view = $changePassword = $delete = $forcelogout = $suspend = '';

											if($authUser->hasRole(['system administrator','treatment centre manager','distributor principal'])){
												$edit =  '<a href="javascript:;" title="Edit User" class="edittreatmentcenteruser" data-treatmentCenterUserId='.$principal_list->id.'><i class="far fa-edit"></i></a>';
												$delete='<a href="javascript:;" title="Delete User"  class="deletetreatmentcenteruser" data-treatmentCenterUserId='.$principal_list->id.' data-treatmentcenterUserName="'.ucfirst($principal_list->name).'"><i class="far fa-trash-alt"></i></a>';
											}

											if($authUser->hasRole(['system administrator','ema service support','treatment centre manager','distributor principal','distributor service','distributor sales'])){
												$changePassword = '<a href="javascript:;" id="'.$principal_list->id.'" data-treatmentcenterUserName="'.ucfirst($principal_list->name).'" title="Reset Password User" class="change-password"><i class="fa fa-key"></i></a>';
												$forcelogout = $principal_list->is_logged_in != 0 ? '<a href="javascript:;" id="'.$principal_list->id.'" data-treatmentcenterUserName="'.ucfirst($principal_list->name).'" class="forcelogout" title="Force Logout User"><i class="fas fa-sign-out-alt"></i></a>' : '';
											}

											if($principal_list->status=="3"){
												$icon = "fas fa-unlock";
												$class = "releasetreatmentcenteruser";
												$title = 'Release User';
											}else{
												$icon = "fas fa-user-lock";
												$class = "suspendtreatmentcenteruser";
												$title = 'Suspend User';
											}
											if($principal_list->treatmentcentre_status!=3 ){
												if($authUser->hasRole(['system administrator','ema service support','treatment centre manager','distributor principal','distributor service'])){
													$suspend='<a href="javascript:;" title="'.$title.'" class="'.$class.'" data-treatmentCenterUserId='.$principal_list->id.' data-treatmentCenterUserName="'.ucfirst($principal_list->name).'"><i class="'.$icon.'"></i></a>';
												}
											}

											if($authUser->hasRole(['system administrator','ema analyst','ema service support','treatment centre manager','distributor principal','distributor sales','distributor service'])){
												$view = '<a href="javascript:;" title="View User"  class="viewtreatmentcenteruser" data-treatmentCenterUserId='.$principal_list->id.'><i class="far fa-eye"></i></a>';
											}
											return $edit.$view.$suspend.$changePassword.$forcelogout.$delete;

										})
										->addColumn('online',function($principal_list){
											$green = asset("images/green_dot.png");
											$red = asset("images/red_dot.png");
											return $principal_list->is_logged_in != 0 ? '<img src='.$green.' border="0" width="15px" style="text-align: center;" class="img-rounded" align="center" />' : '<img src='.$red.' border="0" width="15px" style="text-align: center;" class="img-rounded" align="center" />';
										})
										->rawColumns(['online','action'])->toJson();
									}
								}else{
									$only_priniple = 1;
									if(!empty($getTreatmentCenterId)){
										$getTreatmentCenterData = TreatmentCenter::getTreatmentCenterDetail($getTreatmentCenterId);
										if(isset($getTreatmentCenterData[0]) && !empty($getTreatmentCenterData[0])){
											$treatmentcenterDetails = $getTreatmentCenterData[0];
											return view('treatmentcenter.treatment-center-users',compact('treatmentcenterDetails','getTreatmentCenterId','only_priniple'));
										}else{
											$notification = array(
												'message' => 'No treatment centre found',
												'alert-type' => 'error'
											);
											return redirect('treatment-centre-list')->with($notification);
										}
									}else{
										$notification = array(
											'message' => 'Not allow to view',
											'alert-type' => 'error'
										);
										return redirect('treatment-centre-list')->with($notification);
									}
								}
							}

							/**
							* Upload treatment centre file
							*/
							public function uploadTreatmentCenterFile(Request $request,$id){
								$getTreatmentCenterId = $id;
								$isAdd = '1';
								return view('treatmentcenter.treatment-centre-fileupload',compact('getTreatmentCenterId','isAdd'));
							}

							/**
							* get treatment center filelist
							*/
							public function listtreatmentCentreFiles(Request $request){
								if($request->wantsjson()){

									$getTreatmentCenterId = $request->get('treatmentCenterId');
									if($getTreatmentCenterId){

									$getTreatmentcentreJsonParentData = TreatmentJsonParent::getTreatmentParentJson($getTreatmentCenterId);
									$jsonData = array();
									if(!empty($getTreatmentcentreJsonParentData)){
										foreach($getTreatmentcentreJsonParentData as $value){
											$jsonData[] = $this->calculateTreatmentDataSessionTime($value->id,$value->UID,$value->DSN);
										}
									}

									return Datatables::of($jsonData)
									->addColumn('action', function ($treatment_data) use($getTreatmentCenterId){
										$view = '<a href="'.route('treatment-data-details',[$getTreatmentCenterId,$treatment_data['parent_json_id']]).'" title="View Data" ><i class="far fa-eye"></i></a>';
										return $view;
									})
									->editColumn('s1_tick', function($treatment_data) {
										if($treatment_data['s1_tick']=="Yes"){
											$s1_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$s1_tick = '<i class="far fa-times-circle"></i>';
										}
										return $s1_tick;
									})
									->editColumn('s2_tick', function($treatment_data) {
										if($treatment_data['s2_tick']=="Yes"){
											$s2_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$s2_tick = '<i class="far fa-times-circle"></i>';
										}
										return $s2_tick;
									})
									->editColumn('s3_tick', function($treatment_data) {
										if($treatment_data['s3_tick']=="Yes"){
											$s3_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$s3_tick = '<i class="far fa-times-circle"></i>';
										}
										return $s3_tick;
									})
									->editColumn('s4_tick', function($treatment_data) {
										if($treatment_data['s4_tick']=="Yes"){
											$s4_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$s4_tick = '<i class="far fa-times-circle"></i>';
										}
										return $s4_tick;
									})
									->editColumn('fresh_tick', function($treatment_data) {
										if($treatment_data['fresh_tick']=="Yes"){
											$fresh_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$fresh_tick = '<i class="far fa-times-circle"></i>';
										}
										return $fresh_tick;
									})
									->editColumn('bright_tick', function($treatment_data) {
										if($treatment_data['bright_tick']=="Yes"){
											$bright_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$bright_tick = '<i class="far fa-times-circle"></i>';
										}
										return $bright_tick;
									})
									->editColumn('localDate', function($treatment_data) {
										return $treatment_data['localDate'] =  date('d-m-Y',strtotime($treatment_data['localDate']));
									})
									->rawColumns(['s1_tick','s2_tick','s3_tick','s4_tick','action','fresh_tick','bright_tick'])
									->make(true);
								}else{

									$jsonData = array();
									$authUser = Auth::user();
									$allSerialNumber = array();

									if($authUser->hasRole(['distributor principal']) ||  $authUser->hasRole(['distributor sales'])){
										$getDistributorCompanyData = getDistributorCompanyDataById();
										$getAllAssignedTreatmetnCetre = TreatmentCenter::select('id')->where('distributors',$getDistributorCompanyData->distributor_company_id)->get()->toArray();
										if(!empty($getAllAssignedTreatmetnCetre)){
											$allTreatmentIds =array_column($getAllAssignedTreatmetnCetre,'id');

											if(!empty($allTreatmentIds)){
												$getAllHydraCoolSARPOfTreatmentCentre = HydraCoolSrp::select('serial_number')->whereIn('fk_treatment_centers_id',$getAllAssignedTreatmetnCetre)->get()->toArray();
												if(!empty($getAllHydraCoolSARPOfTreatmentCentre)){
														$allSerialNumber =array_column($getAllHydraCoolSARPOfTreatmentCentre,'serial_number');
												}
											}
										}
									}
									if($authUser->hasRole(['treatment centre manager'])){
											$getAllHydraCoolSARPOfTreatmentCentre = HydraCoolSrp::select('serial_number')->whereIn('fk_treatment_centers_id',$getAllAssignedTreatmetnCetre)->get()->toArray();
											if(!empty($getAllHydraCoolSARPOfTreatmentCentre)){
												$allSerialNumber =array_column($getAllHydraCoolSARPOfTreatmentCentre,'serial_number');
											}
									}

									$getTreatmentcentreJsonParentData = TreatmentJsonParent::getTreatmentParentJson();
									if(!empty($getTreatmentcentreJsonParentData)){
										foreach($getTreatmentcentreJsonParentData as $value){

											if($authUser->hasRole(['distributor principal']) ||  $authUser->hasRole(['distributor sales']) && !empty($allSerialNumber)){
												if(in_array($value->DSN,$allSerialNumber)){
													$jsonData[] = $this->calculateTreatmentDataSessionTime($value->id,$value->UID,$value->DSN);
												}
											}

											if($authUser->hasRole(['treatment centre manager']) && !empty($allSerialNumber)){
												if(in_array($value->DSN,$allSerialNumber)){
														$jsonData[] = $this->calculateTreatmentDataSessionTime($value->id,$value->UID,$value->DSN);
												}
											}

											if($authUser->hasRole(['system administrator']) || $authUser->hasRole(['ema analyst'])){
												$jsonData[] = $this->calculateTreatmentDataSessionTime($value->id,$value->UID,$value->DSN);
											}

										}
									}

									return Datatables::of($jsonData)
									->addColumn('action', function ($treatment_data){
										$view = '<a href="'.route('treatment-data-details',[$treatment_data['treatment_center_id'],$treatment_data['parent_json_id']]).'"  title="View Data" ><i class="far fa-eye"></i></a>';
										return $view;
									})
									->editColumn('s1_tick', function($treatment_data) {
										if($treatment_data['s1_tick']=="Yes"){
											$s1_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$s1_tick = '<i class="far fa-times-circle"></i>';
										}
										return $s1_tick;
									})
									->editColumn('s2_tick', function($treatment_data) {
										if($treatment_data['s2_tick']=="Yes"){
											$s2_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$s2_tick = '<i class="far fa-times-circle"></i>';
										}
										return $s2_tick;
									})
									->editColumn('s3_tick', function($treatment_data) {
										if($treatment_data['s3_tick']=="Yes"){
											$s3_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$s3_tick = '<i class="far fa-times-circle"></i>';
										}
										return $s3_tick;
									})
									->editColumn('s4_tick', function($treatment_data) {
										if($treatment_data['s4_tick']=="Yes"){
											$s4_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$s4_tick = '<i class="far fa-times-circle"></i>';
										}
										return $s4_tick;
									})
									->editColumn('fresh_tick', function($treatment_data) {
										if($treatment_data['fresh_tick']=="Yes"){
											$fresh_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$fresh_tick = '<i class="far fa-times-circle"></i>';
										}
										return $fresh_tick;
									})
									->editColumn('bright_tick', function($treatment_data) {
										if($treatment_data['bright_tick']=="Yes"){
											$bright_tick = '<i class="far fa-check-circle"></i>';
										}else{
											$bright_tick = '<i class="far fa-times-circle"></i>';
										}
										return $bright_tick;
									})
									->rawColumns(['s1_tick','s2_tick','s3_tick','s4_tick','action','fresh_tick','bright_tick'])
									->make(true);
								}

								}else{
									$isAdd = '0';
									$getTreatmentCenterId='';
									return view('treatmentcenter.treatment-centre-fileupload',compact('getTreatmentCenterId','isAdd'));
								}
							}

							/**
							* save treatmentcentre flile
							*/
							public function saveTreatmentCenterFile(Request $request){
								$userInput = array();
								$requestData = $request->all();
								$validator = Validator::make($requestData, [
									'file' =>['required'],
									]);
									if ($validator->fails()) {
										return response()->json(['errors'=>$validator->errors()], 422);
									}else{

										$filedata = $request->file('file');
										if($request->hasFile('file')){
											foreach ($filedata as $file) {
											/*	$filesource = file_get_contents($file);
												$path = Storage::disk('s3Thingstream')->put('root/data.json',$filesource);*/

												if($path){

													/* save data in db*/
													$treatmentCentreFile = new TreatmentCentreFile;
													$treatmentCentreFile->treatmentcentre_id =$requestData['center_id'];
													$treatmentCentreFile->data =$filesource;
													$treatmentCentreFile->uploaded_source ='EMA';
													$treatmentCentreFile->ip_address =request()->ip();
													$treatmentCentreFile->save();
													/* save data in db*/

													$companayName = ucfirst($requestData['center_name']);
													$moduleName = "Treatment centre file";
													$moduleActivity = "Added Treatment Centre file";

													$description="HydraCool SRP Treatement data uploaded for ".$companayName;
													$usercompanyName = getUserCompanyName(Auth::user());

													/*Add action in audit log*/
													captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
													/*Add action in audit log*/

													$message = 'File uploaded';
													$response = [
														'success' => 'true',
														'message' => $message,
													];

												}
											}
											return response()->json($response, 200);
										}
										else{
											$message = 'Something wrong please try again';
											$response = [
												'success' => 'false',
												'message' => $message,

											];
											return response()->json($response, 500);
										}
									}
								}

								public function verifyTreatmentEmail(Request $request){
									if ($request->hasValidSignature()) {
										$email = $request->query('email');
										$treatmentCenter = TreatmentCenter::where('email_of_primary_contact',$email)->where('status','!=','2')->first();
										//dd($distributor);
										if($treatmentCenter && $treatmentCenter->count() > 0){
											if($treatmentCenter->is_verified != 0){
												$message = "Your email has been already verified";
												$notification = array(
													'message' => $message,
													'alert-type' => 'error'
												);
											}else{
												$treatmentCenter_verify = TreatmentCenter::find($treatmentCenter->id);
												$treatmentCenter_verify->is_verified = 1;
												$treatmentCenter_verify->verified_at = new DateTime;
												$treatmentCenter_verify->save();
												$message = "Your email has been verified";
												$notification = array(
													'message' => $message,
													'alert-type' => 'success'
												);
											}
										}else{
											$message = "Your link has been expired or invalid.";
											$notification = array(
												'message' => $message,
												'alert-type' => 'error'
											);
										}
									}else{
										$message = "Your link has been expired or invalid";
										$notification = array(
											'message' => $message,
											'alert-type' => 'error'
										);
									}
									return redirect('/login')->with($notification);
								}

								public function treatmentcenterUniqueCode(Request $request){
									if($request->ajax()){
										if($request->id){
											$treatmentcenter=TreatmentCenter::where('treatment_ema_code',$request->treatment_ema_code)
											->where('id','<>',$request->id)
											->where('status','!=',2)
											->first();
										}else{
											$treatmentcenter=TreatmentCenter::where('treatment_ema_code',$request->treatment_ema_code)
											->where('status','!=',2)
											->first();
										}
										if($treatmentcenter){
											return response()->json(false);
										}else{
											return response()->json(true);
										}
									}
								}


								public function getTreatmentCentreDetails(Request $request)
								{
									$getSerialNumber = $request->input('serial_number');
									$hydracoolSRPUnitDetails = array();
									$getTreatmentCentreDetails = TreatmentCenter::getTreatmentCentreDetails($getSerialNumber);
									if(!empty($getTreatmentCentreDetails))
									{
										/* Get hydraCool SRP unit details */
										$getSRPUnitData = 	HydraCoolSrp::getHydraCoolSRPById($getTreatmentCentreDetails[0]['hydracoolspr_id']);
										/* Get hydraCool SRP unit details */

										if(!empty($getSRPUnitData))
										{
											$hydracoolSRPUnitDetails = json_decode($getSRPUnitData[0]['title']);
										}
										$data = array('success'=>1,'status'=>true);
									}else{
										$data = array('success'=>0,'status'=>false);
									}
									return response()->json($data, 200);
								}


								public function getTreatmentJsonData(Request $request){
									$treatment_data_json_id = $request->input('treatment_data_json_id');

									$getTreatmentCentreJsonData = TreatmentCentreFile::select('data')
									->where('fk_reference_id',$treatment_data_json_id)
									->first();

									if(!empty($getTreatmentCentreJsonData))
									{
										$data = array('success'=>1,'status'=>true,'treatment_data'=>$getTreatmentCentreJsonData);
									}else{
										$data = array('success'=>0,'status'=>false);
									}
									return response()->json($data, 200);
								}

								public function editTreatmentJsonData(Request $request){
									$treatment_data_json_id = $request->input('treatment_data_json_id');

									$getTreatmentCentreJsonData = TreatmentJson::editTreatmentJson($treatment_data_json_id);

									if(!empty($getTreatmentCentreJsonData))
									{
										$data = array('success'=>1,'status'=>true,'treatment_json_data'=>$getTreatmentCentreJsonData);
									}else{
										$data = array('success'=>0,'status'=>false);
									}
									return response()->json($data, 200);
								}

								public function treatmentDataDetails($centreId,$parentjsonId){
									$treatmentcenterDetails = array();
									$getTreatmentData = array();
									$arrDeviceSerialNumber = array();
									$totalArray = array();
									$arrCSNData = array();
									$arrParentWAEData =  array();
									/* Check serial number is assign to treatment centre or not */
									$getTreatmentcentreJsonParentData = TreatmentJsonParent::find($parentjsonId);

									if(!empty($getTreatmentcentreJsonParentData)){

										$getTreatmentCenterData = TreatmentCenter::getTreatmentCenterDetail($centreId);
										if(isset($getTreatmentCenterData[0]) && !empty($getTreatmentCenterData[0])){
											$treatmentcenterDetails = $getTreatmentCenterData[0];
										}

										$DSN = $getTreatmentcentreJsonParentData->DSN;
										$UID = $getTreatmentcentreJsonParentData->UID;
										$parentTreatmentId = $getTreatmentcentreJsonParentData->id;
										$treatmentjsonData = $this->calculateTreatmentDataSessionTime($parentTreatmentId,$UID,$DSN);

										$convertData = convertInHoursSecords($treatmentjsonData['sessionTime']);
										$treatmentjsonData['sessionTimeHour'] = $convertData[0];
										$treatmentjsonData['sessionTimeSeconds'] = $convertData[1];

										$convertUltraBSessionTimeData = convertInHoursSecords($treatmentjsonData['UltraBSessionTime']);
										$treatmentjsonData['UltraBSessionTimeHour'] = $convertUltraBSessionTimeData[0];
										$treatmentjsonData['UltraBSessionTimeSeconds'] = $convertUltraBSessionTimeData[1];
										$convertVibroXSessionTimeData = convertInHoursSecords($treatmentjsonData['VibroXSessionTime']);
										$treatmentjsonData['VibroXSessionTimeHour'] = $convertVibroXSessionTimeData[0];
										$treatmentjsonData['VibroXSessionTimeSeconds'] = $convertVibroXSessionTimeData[1];
										$convertMicroTSessionTimeData = convertInHoursSecords($treatmentjsonData['MicroTSessionTime']);
										$treatmentjsonData['MicroTSessionTimeHour'] = $convertMicroTSessionTimeData[0];
										$treatmentjsonData['MicroTSessionTimeSeconds'] = $convertMicroTSessionTimeData[1];
										$convertCollagenSessionTimeData = convertInHoursSecords($treatmentjsonData['CollagenSessionTime']);
										$treatmentjsonData['CollagenSessionTimeHour'] = $convertCollagenSessionTimeData[0];
										$treatmentjsonData['CollagenSessionTimeSeconds'] = $convertCollagenSessionTimeData[1];
										$convertAquaBSessionTimeData = convertInHoursSecords($treatmentjsonData['AquaBSessionTime']);
										$treatmentjsonData['AquaBSessionTimeHour'] = $convertAquaBSessionTimeData[0];
										$treatmentjsonData['AquaBSessionTimeSeconds'] = $convertAquaBSessionTimeData[1];

										/* Get hydracool SRP units */
										$getHydraCoolSRPData = HydraCoolSrp::select('id')->where('fk_treatment_centers_id',$centreId)->where('serial_number',$DSN)->first();

										$getHydraCoolSRPUnitData = HydraCoolSrp::getHydracoolSrpUnitDetails($getHydraCoolSRPData->id);

										if(!empty($getHydraCoolSRPUnitData)){
											$arrDeviceSerialNumber = json_decode($getHydraCoolSRPUnitData->title);
										}
										/* Get hydracool SRP units */

										$getTreatmentJsonData = TreatmentJson::getTreatmentJson($parentTreatmentId);

										/* calculate solution data */
										if(!empty($getTreatmentJsonData)){
											$arrParentSkinData = array();
											$arrParentSolutionData =  array();

											$arrFinal =  array();
											$arrRootFinal = array();
											$modFlag = 0;
											$skinTypeFlag = 0;
											$arrPARMergeData = array();

											foreach($getTreatmentJsonData as $getTreatmentJson){
												$arrSkinData = array();
												/* check technology */
												$getTechnology = json_decode($getTreatmentJson->TEC);
												/* check technology */
												if(!empty($arrFinal)){
													$arrayDiff = array_diff(json_decode($arrFinal['SKC']),json_decode($getTreatmentJson->SKC));
													if($getTreatmentJson->MOD==$arrFinal['mode'] &&  $getTreatmentJson->SKT==$arrFinal['SKT'] && count($arrayDiff) == 0){
														$modFlag = 1;
													}else{
														$modFlag = 0;
													}
												}

												/* Get Treament data PAR */
												$arrPARData = array();
												$arrParentPARData =  array();
												$gettreatmentCentrePARData = TreatmentCentrePAR::getJSONPARData($getTreatmentJson->id);


												foreach($gettreatmentCentrePARData as $gettreatmentCentrePAR){
													/* Get json raw data */
													$getTreatmentCentreJsonData = TreatmentCentreFile::select('data')
													->where('fk_reference_id',$getTreatmentJson->id)
													->first();
													/* Get json raw data */

													$arrPARData['intensity_of_vacuum'] =$gettreatmentCentrePAR['intensity_of_vacuum'];
													$arrPARData['vacuum'] =$gettreatmentCentrePAR['vacuum'];
													$arrPARData['pulsed'] =$gettreatmentCentrePAR['pulsed'];
													$arrPARData['flow'] =$gettreatmentCentrePAR['flow'];
													$arrPARData['bottle'] =  $gettreatmentCentrePAR['bottle'];
													$arrPARData['mode_selected'] =  getModeName($gettreatmentCentrePAR['mode_selected']);
													$arrPARData['technology'] =  $getTechnology['1'];
													$arrPARData['treatment_area'] =  $getTechnology['0'];
													$arrPARData['treatment_time'] =  $gettreatmentCentrePAR['treatment_time'];
													$arrPARData['rawJsonData'] =  !empty($getTreatmentCentreJsonData) ? $getTreatmentCentreJsonData['data'] : '';
													$arrParentPARData[] = $arrPARData;
												}

												if($modFlag==1){
													$getLastPARFromRoot = end($arrRootFinal);
													$getLastPARKeyFromRoot = array_key_last($arrRootFinal);
													$arrayMerge = array_merge($getLastPARFromRoot['PAR'],$arrParentPARData);
													$arrRootFinal[$getLastPARKeyFromRoot]['PAR'] = $arrayMerge;
												}else{
													$arrFinal["PAR"] = $arrParentPARData;
													$arrFinal['mode'] = $getTreatmentJson->MOD;
													$arrFinal['SKC'] =  $getTreatmentJson->SKC;
													$arrFinal['SKT'] =  $getTreatmentJson->SKT;
												}
												/* Get Treament data PAR */


												/* Get Skin data */
												$arrPARData = array();

												if($getTechnology['1']=="AQ"){

													/* get CSN Solution Data */
													$getTreatmentJsonCSNData = TreatmentCentreCSN::getJSONCSNData($getTreatmentJson->id);

													if(!empty($getTreatmentJsonCSNData)){
														$arrStartSolutionData =  array();
														$arrEndSolutionData =  array();
														foreach($getTreatmentJsonCSNData as $getTreatmentJsonCSN){
															$getSolutionNumber = getFirstCharacterFromString($getTreatmentJsonCSN['bottle_id']);
															$arrStartSolutionData["S".$getSolutionNumber] = JSON_SOLUTION_BASE_VALUE*$getTreatmentJsonCSN['initial_value']/100;
															$arrEndSolutionData["S".$getSolutionNumber] = JSON_SOLUTION_BASE_VALUE*$getTreatmentJsonCSN['end_value']/100;
															$arrPARData['starting_solution'] = $arrStartSolutionData;
															$arrPARData['edning_solution'] = $arrEndSolutionData;
															$arrCSNData[$getTreatmentJsonCSN['fk_treatment_json_id']] = $arrPARData;
														}
													}
													/* get CSN Solution Data */
												}
												if($modFlag==0){
													$arrRootFinal[] = $arrFinal;
												}

												/* get Diagnostics error code and value */
												$gettreatmentCentreWAEData = TreatmentCentreWAE::getJSONWAEData($getTreatmentJson->id);
												$arrWAEData = array();

												if(!empty($gettreatmentCentreWAEData)){
													foreach($gettreatmentCentreWAEData as $gettreatmentCentreWAE){
														$getFirstCharacterFromString = getFirstCharacterFromString($gettreatmentCentreWAE['warning_code']);
														if($getFirstCharacterFromString=="E"){
																$arrWAEData['component'] = $DSN;
																$arrWAEData['date'] = date('d-m-Y',strtotime($gettreatmentCentreWAE['warning_datetime']));
																$arrWAEData['time'] = date('H:i:s',strtotime($gettreatmentCentreWAE['warning_datetime']));
																$arrWAEData['code'] = $gettreatmentCentreWAE['warning_code'];
																$arrWAEData['value'] = ErrorCode::getCodeValueByCode($gettreatmentCentreWAE['warning_code']);
																$arrParentWAEData[] = $arrWAEData;
														}
													}
												}
												/* get Diagnostics error code and value */
											}
										}
										/* calculate solution data */
										return view('treatmentcenter.treatment-data-details',compact('treatmentcenterDetails','UID','DSN','treatmentjsonData','arrDeviceSerialNumber','arrRootFinal','arrCSNData','arrParentWAEData'));

									}else{
										$notification = array(
											'message' => 'No treatment data found',
											'alert-type' => 'error'
										);
										return redirect()->route('view-treatment-center',['id'=>$centreId])->with($notification);
									}
									/* Check sernal number is assign to treatment centre or not */
								}

								public function calculateTreatmentDataSessionTime($treatmentId,$UID,$DSN){
									$tempArray = array();
									$finalArray = array();
									$getTreatmentJson = TreatmentJson::getTreatmentJson($treatmentId);

									$arrUltraB = array();
									$arrVibroX = array();
									$arrMicroT = array();
									$arrCollagen = array();
									$arrAquaB = array();
									$allAquaBtechnologyId = array();
									$allAquaBAllBottleArray = array();

									$allvibroXtechnologyId  = array();
									$allvibroXAllBottleArray = array();

									foreach($getTreatmentJson as $key=>$getTreatmentChildJson){
										$checkTechnology = json_decode($getTreatmentChildJson->TEC);

										if($checkTechnology[1]=="UL"){
											if(!empty($arrUltraB)){
												$UltraBstartTime = strtotime($getTreatmentJson[$arrUltraB['ultrab_previous_key']]->USI);
												$UltraBendTime = strtotime($getTreatmentChildJson->USF);
												$ultrab_session_time =  round(abs($UltraBendTime - $UltraBstartTime) / 60,2);
												$arrUltraB['ultrab_session_time'] = $ultrab_session_time;

											}else{
												$UltraBstartTime = strtotime($getTreatmentChildJson->USI);
												$UltraBendTime = strtotime($getTreatmentChildJson->USF);
												$ultrab_session_time =  round(abs($UltraBendTime - $UltraBstartTime) / 60,2);
												$arrUltraB['ultrab_session_time'] = $ultrab_session_time;
												$arrUltraB['ultrab_previous_key'] = $key;
											}

										}else if($checkTechnology[1]=="CO"){
											if(!empty($arrCollagen)){
												$CollagenstartTime = strtotime($getTreatmentJson[$arrCollagen['collagen_previous_key']]->USI);
												$CollagenendTime = strtotime($getTreatmentChildJson->USF);
												$collagen_session_time =  round(abs($CollagenendTime - $CollagenstartTime) / 60,2);
												$arrCollagen['collagen_session_time'] = $collagen_session_time;

											}else{
												$CollagenstartTime = strtotime($getTreatmentChildJson->USI);
												$CollagenendTime = strtotime($getTreatmentChildJson->USF);
												$collagen_session_time =  round(abs($CollagenendTime - $CollagenstartTime) / 60,2);
												$arrCollagen['collagen_session_time'] = $collagen_session_time;
												$arrCollagen['collagen_previous_key'] = $key;
											}

										}else if($checkTechnology[1]=="MI"){

											if(!empty($arrMicroT)){
												$MicroTBstartTime = strtotime($getTreatmentJson[$arrMicroT['microt_previous_key']]->USI);
												$MicroTBendTime = strtotime($getTreatmentChildJson->USF);
												$microt_session_time =  round(abs($MicroTBendTime - $MicroTBstartTime) / 60,2);
												$arrMicroT['microt_session_time'] = $microt_session_time;
											}else{
												$MicroTBstartTime = strtotime($getTreatmentChildJson->USI);
												$MicroTBendTime = strtotime($getTreatmentChildJson->USF);
												$microt_session_time =  round(abs($MicroTBendTime - $MicroTBstartTime) / 60,2);
												$arrMicroT['microt_session_time'] = $microt_session_time;
												$arrMicroT['microt_previous_key'] = $key;
											}

										}else if($checkTechnology[1]=="VI"){
											if(!empty($arrVibroX)){
												$VibroXBstartTime = strtotime($getTreatmentJson[$arrVibroX['vibrox_previous_key']]->USI);
												$VibroXBendTime = strtotime($getTreatmentChildJson->USF);
												$vibrox_session_time =  round(abs($VibroXBendTime - $VibroXBstartTime) / 60,2);
												$arrVibroX['vibrox_session_time'] = $vibrox_session_time;
											}else{
												$VibroXBstartTime = strtotime($getTreatmentChildJson->USI);
												$VibroXBendTime = strtotime($getTreatmentChildJson->USF);
												$vibrox_session_time =  round(abs($VibroXBendTime - $VibroXBstartTime) / 60,2);
												$arrVibroX['vibrox_session_time'] = $vibrox_session_time;
												$arrVibroX['vibrox_previous_key'] = $key;
											}
											$allvibroXtechnologyId[] = $getTreatmentChildJson->id;
										}else if($checkTechnology[1]=="AQ"){
											if(!empty($arrAquaB)){
												$AquaBstartTime = strtotime($getTreatmentJson[$arrAquaB['Aquab_previous_key']]->USI);
												$AquaBendTime = strtotime($getTreatmentChildJson->USF);
												$AquaB_session_time =  round(abs($AquaBendTime - $AquaBstartTime) / 60,2);
												$arrAquaB['Aquab_session_time'] = $AquaB_session_time;
											}else{
												$AquaBstartTime = strtotime($getTreatmentChildJson->USI);
												$AquaBendTime = strtotime($getTreatmentChildJson->USF);
												$AquaB_session_time =  round(abs($AquaBstartTime - $AquaBendTime) / 60,2);
												$arrAquaB['Aquab_session_time'] = $AquaB_session_time;
												$arrAquaB['Aquab_previous_key'] = $key;
											}
											$allAquaBtechnologyId[] = $getTreatmentChildJson->id;
										}
									}

									$totalData = count($getTreatmentJson);
									$tempArray['client_id'] = $UID;
									$tempArray['localDate'] = date('d-m-Y',strtotime($getTreatmentJson[0]->USI));
									$tempArray['localTime'] = date('H:i:s',strtotime($getTreatmentJson[0]->USI));
									$tempArray['uploaddate'] = date('d-m-Y',strtotime($getTreatmentJson[0]->UTI));
									$tempArray['uploadtime'] = date('H:i:s',strtotime($getTreatmentJson[0]->UTI));
									$tempArray['parent_json_id'] = $treatmentId;
									$tempArray['imsi'] = $getTreatmentJson[0]->imsi;
									$tempArray['treatment_center_id'] = HydraCoolSrp::getTreatmentCentreIdBySerialNumber($DSN);

									$startTime = strtotime($getTreatmentJson[0]->USI);

									if($totalData > 1){
										$endTime = strtotime($getTreatmentJson[$totalData-1]->USF);
									}else{
										$endTime = strtotime($getTreatmentJson[$totalData-1]->USF);
									}

									/* Create bottle and mod selected tick Indicator */
									if(!empty($allAquaBtechnologyId)){
										$allAquaBAllBottleArray = TreatmentCentrePAR::getBottleValue($allAquaBtechnologyId);
										if(!empty($allAquaBAllBottleArray)){
											$tempArray['s1_tick'] =  in_array(1,$allAquaBAllBottleArray) ? "Yes" : "No" ;
											$tempArray['s2_tick'] =  in_array(2,$allAquaBAllBottleArray) ? "Yes" : "No" ;
											$tempArray['s3_tick'] =  in_array(3,$allAquaBAllBottleArray) ? "Yes" : "No" ;
											$tempArray['s4_tick'] =  in_array(4,$allAquaBAllBottleArray) ? "Yes" : "No" ;
										}else{
											$tempArray['s1_tick'] =  "No";
											$tempArray['s2_tick'] =  "No" ;
											$tempArray['s3_tick'] =  "No" ;
											$tempArray['s4_tick'] =  "No" ;
										}
									}
									if(!empty($allvibroXtechnologyId)){

										$allvibroXAllBottleArray = TreatmentCentrePAR::getModSelectedValue($allvibroXtechnologyId);
										if(!empty($allvibroXAllBottleArray)){
											$tempArray['fresh_tick'] =  in_array(1,$allvibroXAllBottleArray) ? "Yes" : "No" ;
											$tempArray['bright_tick'] =  in_array(2,$allvibroXAllBottleArray) ? "Yes" : "No" ;
										}
									}else{
										$tempArray['fresh_tick'] =  "No" ;
										$tempArray['bright_tick'] =  "No" ;
									}
									/* Create bottle and mod selected tick Indicator */

									$tempArray['sessionTime'] =  round(abs($endTime - $startTime) / 60,2);
									$tempArray['UltraBSessionTime'] = isset($arrUltraB['ultrab_session_time']) ?  $arrUltraB['ultrab_session_time'] : '--';
									$tempArray['VibroXSessionTime'] = isset($arrVibroX['vibrox_session_time']) ? $arrVibroX['vibrox_session_time'] : '--';
									$tempArray['MicroTSessionTime'] = isset($arrMicroT['microt_session_time']) ? $arrMicroT['microt_session_time'] : '--';
									$tempArray['CollagenSessionTime'] = isset($arrCollagen['collagen_session_time']) ? $arrCollagen['collagen_session_time'] : '';
									$tempArray['AquaBSessionTime'] = isset($arrAquaB['Aquab_session_time']) ? $arrAquaB['Aquab_session_time'] : '--';
									$tempArray['serial_number'] = $DSN;

									return $tempArray;
								}

							}
