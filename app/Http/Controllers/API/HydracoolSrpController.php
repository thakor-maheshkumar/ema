<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\HydraCoolSrp;
use App\HydraCoolSrpUnits;
use App\TreatmentCenter;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDynamicEmail;
use DB;

class HydracoolSrpController extends Controller
{
    public $successStatus = 200;


	/**
	* add hydracool srp and hydracool srp unit
	* @return json
	*/
	public function addHydracoolSrp(Request $request){
			$userInput = array();
			$requestData = $request->all();
			$input = $requestData['formData'];

			$validator = Validator::make($input, [
				'treatment_center_id' =>['required','numeric'],
				'serial_number' =>'required|unique:hydracool_srp,serial_number,2,status',
				'srp_units' =>['required'],
				'manufacturer_name' =>['required'],
				'manufacturing_date' =>['required'],
				'sale_date' =>['required'],
				]);

				if ($validator->fails()) {
					return response()->json(['errors'=>$validator->errors()], 422);
				}else{
					if($input['is_demo']==1){
						$is_demo = 1;
					}else{
						$is_demo = 0;
					}

					/*Add hyracool srp*/
					$hyracoolSrp = new HydraCoolSrp;
					$hyracoolSrp->fk_treatment_centers_id = $input['treatment_center_id'];
					$hyracoolSrp->serial_number = $input['serial_number'];
					$hyracoolSrp->is_demo = $is_demo;
					$hyracoolSrp->created_by = Auth::user()->id;
					$hyracoolSrp->manufacturer_name = $input['manufacturer_name'];
					$hyracoolSrp->manufacturing_date = date('Y-m-d', strtotime($input['manufacturing_date']));
					$hyracoolSrp->sale_date = date('Y-m-d', strtotime($input['sale_date']));
					$hyracoolSrp->ip_address = request()->ip();
					$hyracoolSrp->save();
					if($hyracoolSrp){

						/*Add action in audit log*/

						/* Get treatment centre name by Id */
						 $getTreatmentCentreData = TreatmentCenter::getTreatmentCenterDetail($input['treatment_center_id']);
						 /* Get treatment centre name by Id */

						 $getCompanyName = '';
						 if(!empty($getTreatmentCentreData[0])){
								 $getCompanyName = ucfirst($getTreatmentCentreData[0]->full_company_name);
						 }
						 $usercompanyName = getUserCompanyName(Auth::user());
						 /* Merge company name and Srial number in order to show serial number in  audit log*/
						 		$companyName = $usercompanyName."/".$input['serial_number'];
						 /* Merge company name and Srial number in order to show serial number in  audit log*/


						$moduleName = 'HydraCool SRP Device';
						$moduleActivity = 'Added HydraCool SRP Device';
						// $description = "HydraCool SRP unit serial number ".$input['serial_number']." added to Treatment centre ".$getCompanyName.".";
						$description="HydraCool SRP unit ".$input['serial_number']." has been added to Treatment Centre ".$getCompanyName;

						captureAuditLog($moduleName,$moduleActivity,$description,$input,$companyName);
						/*Add action in audit log*/

						if(isset($input['treatment_center_id']) && $input['treatment_center_id'] != ''){
							$treatment_Details = TreatmentCenter::getTreatmentCenterDetail($input['treatment_center_id']);
							if($treatment_Details->count()){
								$data['slug'] = 'device_allocated';
								$data['treatment_ema_code'] = $treatment_Details[0]->treatment_ema_code;
								$data['name_of_primary_contact'] = $treatment_Details[0]->name_of_primary_contact;
								$data['serial_number'] =  $input['serial_number'];
								$data['full_company_name'] = $treatment_Details[0]->full_company_name;
								Mail::to($treatment_Details[0]->email_of_primary_contact)->queue(new SendDynamicEmail($data));

								$moduleName = 'Email';
								$moduleActivity = 'Email logged for HydraCool SRP Added';
								$description = 'Email has been sent to Treatment centre primary user : '.ucfirst($treatment_Details[0]->name_of_primary_contact);
								$requestData = array('treatmentcentre_id'=>$treatment_Details[0]->id);

								/*Add action in audit log*/
										captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
								/*Add action in audit log*/
							}
						}

						$addedHydraCoolSrpID = $hyracoolSrp->id;
						/*Add hydracool spr units*/
						$getAllUniteName = $input['srp_units'];
						if(!empty($getAllUniteName)){
							$UnitsRecord[] = [
								'fk_hydracool_srp_id' => $addedHydraCoolSrpID,
								'title' => json_encode($getAllUniteName),
								'ip_address'=>request()->ip()
							];
							HydraCoolSrpUnits::insert($UnitsRecord);
						}
						/*Add hydracool spr units*/

						$message = 'HydraCool SRP Device added successfully';
						$response = [
							'success' => 'true',
							'message' => $message,
							'hydracool_srp_id'=>$addedHydraCoolSrpID
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
					/*Add hyracool srp*/

				}
  }


  /**
	* update hydracool srp and hydracool srp unit
	* @return json
	*/
	public function updateHydracoolSrp(Request $request){
		$userInput = array();
		$requestData = $request->all();
		$input = $requestData['formData'];

		$validator = Validator::make($input, [
			'treatment_center_id' =>['required','numeric'],
			'serial_number' =>['required', 'string'],
			'srp_units' =>['required'],
			'is_demo' =>['required'],
			'manufacturer_name' =>['required'],
			'manufacturing_date' =>['required'],
			'sale_date' =>['required'],
			]);

			if ($validator->fails()) {
				return response()->json(['error'=>$validator->errors()], 401);
			}else{
				$treatmentCenterId = $input['treatment_center_id'];
				$hydracoolSrpId = $input['hydracool_srp_id'];

				/*update hyracool srp*/
				$hyracoolSrp = HydraCoolSrp::find($hydracoolSrpId);
				$usercompanyName = getUserCompanyName(Auth::user());
				/* Get original Data  before update*/
				$originalData = getOriginalData($hyracoolSrp);
				/* Get original Data  before update*/

				if(!empty($hyracoolSrp)){

					/*Check if current treatment center and previous treatment center is same*/
					$getPreviouseTreatmentCenterId = $hyracoolSrp->fk_treatment_centers_id;
					if($treatmentCenterId == $getPreviouseTreatmentCenterId){
						$hyracoolSrp->serial_number = $input['serial_number'];
						$hyracoolSrp->ip_address = request()->ip();
						$hyracoolSrp->created_by = Auth::user()->id;
						$hyracoolSrp->is_demo = $input['is_demo'];
						$hyracoolSrp->manufacturer_name = $input['manufacturer_name'];
						$hyracoolSrp->manufacturing_date = date('Y-m-d', strtotime($input['manufacturing_date']));
						$hyracoolSrp->sale_date = date('Y-m-d', strtotime($input['sale_date']));
						$hyracoolSrp->save();
						/*update hydracool srp units*/

						/* Get treatment centre name by Id */
								$getTreatmentCentreData = TreatmentCenter::getTreatmentCenterDetail($treatmentCenterId);
						/* Get treatment centre name by Id */

							$getCompanyName = '';
							if(!empty($getTreatmentCentreData[0])){
									$getCompanyName = ucfirst($getTreatmentCentreData[0]->full_company_name);
							}

								/* Merge company name and Srial number in order to show serial number in  audit log*/
								$companyName = $usercompanyName."/".$input['serial_number'];
								/* Merge company name and Srial number in order to show serial number in  audit log*/


						$getAllUniteName = $input['srp_units'];
						if(!empty($getAllUniteName)){
							HydraCoolSrpUnits::where('fk_hydracool_srp_id',$hydracoolSrpId)->delete();
							$UnitsRecord[] = [
								'fk_hydracool_srp_id' => $hydracoolSrpId,
								'title' => json_encode($getAllUniteName),
								'ip_address'=>request()->ip()
							];
							HydraCoolSrpUnits::insert($UnitsRecord);

							$moduleName = 'HydraCool SRP Device';
							$moduleActivity = 'Updated HydraCool SRP';
							$description = "HydraCool SRP unit ".$input['serial_number']." has been amended for Treatment Centre ".$getCompanyName;
							captureAuditLog($moduleName,$moduleActivity,$description,$input,$companyName);
							/*Add action in audit log*/

						}
						/*update hydracool srp units*/
					}
					/*Check if current treatment center and previous treatment center is same*/

					/*check if treatment center has been changed*/
					else{
						/*delete the previous allocated hydracool srp and its units*/
						$findHyracoolSrp = HydraCoolSrp::find($hydracoolSrpId);
						$findHyracoolSrp->status = 0;
						$findHyracoolSrp->save();
						HydraCoolSrpUnits::where('fk_hydracool_srp_id', $hydracoolSrpId)->update(['status' => 0]);
						/*delete the previous allocated hydracool srp and its units*/
						if(isset($getPreviouseTreatmentCenterId) && $getPreviouseTreatmentCenterId != ''){
							$treatment_Details = TreatmentCenter::getTreatmentCenterDetail($getPreviouseTreatmentCenterId);
							if($treatment_Details->count()){
								$data['slug'] = 'device_allocated_removed';
								$data['treatment_ema_code'] = $treatment_Details[0]->treatment_ema_code;
								$data['name_of_primary_contact'] = $treatment_Details[0]->name_of_primary_contact;
								$data['serial_number'] =  $input['serial_number'];
								$data['full_company_name'] = $treatment_Details[0]->full_company_name;
								Mail::to($treatment_Details[0]->email_of_primary_contact)->queue(new SendDynamicEmail($data));

								/* Merge company name and Srial number in order to show serial number in  audit log*/
									$companyName = $usercompanyName."/".$input['serial_number'];
								/* Merge company name and Srial number in order to show serial number in  audit log*/

								$moduleName = 'Email';
								$moduleActivity = 'Email Logged for HydraCool SRP Update';
								$description = "Email has been sent to Treatment centre primary user: ". $treatment_Details[0]->name_of_primary_contact;
								captureAuditLog($moduleName,$moduleActivity,$description,$input,$companyName);
								/*Add action in audit log*/

							}
						}
						/*assign the new treatment center to this hydracool srp*/
						$hyracoolSrp = new HydraCoolSrp;
						$hyracoolSrp->fk_treatment_centers_id = $input['treatment_center_id'];
						$hyracoolSrp->serial_number = $input['serial_number'];
						$hyracoolSrp->is_demo = $input['is_demo'];
						$hyracoolSrp->created_by = Auth::user()->id;
						$hyracoolSrp->manufacturer_name = $input['manufacturer_name'];
						$hyracoolSrp->manufacturing_date = date('Y-m-d', strtotime($input['manufacturing_date']));
						$hyracoolSrp->sale_date = date('Y-m-d', strtotime($input['sale_date']));
						$hyracoolSrp->ip_address = request()->ip();
						$hyracoolSrp->save();

						if($hyracoolSrp){

							/*Add action in audit log*/

							/* Get New Treatment centre details*/

								/* Get treatment centre name by Id */
										$getTreatmentCentreData = TreatmentCenter::getTreatmentCenterDetail($input['treatment_center_id']);
										/* Get treatment centre name by Id */

										$getCompanyName = '';
										if(!empty($getTreatmentCentreData[0])){
												$getCompanyName = ucfirst($getTreatmentCentreData[0]->full_company_name);
										}

										/* Merge company name and Srial number in order to show serial number in  audit log*/
												$companyName = $usercompanyName."/".$input['serial_number'];
										/* Merge company name and Srial number in order to show serial number in  audit log*/
							/* Get New Treatment centre details*/

							/* Get old Treatment centre details*/
												/* Get treatment centre name by Id */
												$getoldTreatmentCentreData = TreatmentCenter::getTreatmentCenterDetail($getPreviouseTreatmentCenterId);
												/* Get treatment centre name by Id */

												$getoldCompanyName = '';
												if(!empty($getoldTreatmentCentreData[0])){
														$getoldCompanyName = ucfirst($getoldTreatmentCentreData[0]->full_company_name);
												}
							/* Get old Treatment centre details*/

							$moduleName = 'HydraCool SRP Device';
							$moduleActivity = 'De-allocated HydraCool SRP Device';
							// $description = "HydraCool SRP unit serial number ".$input['serial_number']." removed from Treatment centre ".$getoldCompanyName." and added to new Treatment centre ".$getCompanyName.".";
							$description = "HydraCool SRP unit ".$input['serial_number']." has been de-allocated from Treatment Centre ".$getoldCompanyName." and allocated to Treatment Centre ".$getCompanyName;

							captureAuditLog($moduleName,$moduleActivity,$description,$input,$companyName);
							/*Add action in audit log*/

							$addedHydraCoolSrpID = $hyracoolSrp->id;

							/*Add hydracool spr units*/
							$getAllUniteName = $input['srp_units'];
								$UnitsRecord[] = [
									'fk_hydracool_srp_id' => $addedHydraCoolSrpID,
									'title' => json_encode($getAllUniteName),
									'ip_address'=>request()->ip()
								];
							HydraCoolSrpUnits::insert($UnitsRecord);

							if(isset($treatmentCenterId) && $treatmentCenterId != ''){
								$treatment_Details = TreatmentCenter::getTreatmentCenterDetail($treatmentCenterId);
								if($treatment_Details->count()){
									$data['slug'] = 'device_allocated';
									$data['treatment_ema_code'] = $treatment_Details[0]->treatment_ema_code;
									$data['name_of_primary_contact'] = $treatment_Details[0]->name_of_primary_contact;
									$data['serial_number'] =  $input['serial_number'];
									$data['full_company_name'] = $treatment_Details[0]->full_company_name;
									Mail::to($treatment_Details[0]->email_of_primary_contact)->queue(new SendDynamicEmail($data));


								/* Merge company name and Srial number in order to show serial number in  audit log*/
								$companyName = $usercompanyName."/".$input['serial_number'];
								/* Merge company name and Srial number in order to show serial number in  audit log*/

								$moduleName = 'Email';
								$moduleActivity = 'Email Logged for HydraCool SRP Added';
								$description = "Email has been sent to Treatment Centre primary user: ". $treatment_Details[0]->name_of_primary_contact;
								captureAuditLog($moduleName,$moduleActivity,$description,$input,$companyName);
								/*Add action in audit log*/
								}
							}
							/*Add hydracool spr units*/
						}
						/*assign the new treatment center to this hydracool srp*/
					}
					/*check if treatment center has been changed*/

					if($hyracoolSrp){
						$addedHydraCoolSrpID = $hyracoolSrp->id;
						$message = 'HydraCool SRP Device updated successfully';
						$response = [
							'success' => 'true',
							'message' => $message,
							'hydracool_srp_id'=>$addedHydraCoolSrpID
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
					$message = 'HydraCool SRP Device not found';
					$response = [
						'success' => 'fail',
						'message' => $message,
					];
					return response()->json($response, $this->successStatus);
				}
				/*update hyracool srp*/
			}
	}

	/**
	* Check serial number unique
	* @return true/false
	*/
	public function checkSerialNumberUnique(Request $request){
		$serialNumber = $request->input('serial_number');
		$exceptId = $request->input('exceptId');
		$getCount = HydraCoolSrp::checkSerialNumberUnique($serialNumber,$exceptId);
		echo $getCount;
	}


  /**
  * get srp serial number from treatment center id
  * @return json
	*/
  public function getHydraCoolSRPList(Request $request){

		/*get All treatmentCenterId which is active and under ema*/

		$getHydracoolSrpData = HydraCoolSrp::getHydraCoolSrpSerialNumber();
		if(!empty($getHydracoolSrpData)){
			$serialNumberArray = array();
			foreach ($getHydracoolSrpData as $getHydracoolSrpRow) {
				$serialNumberArray[$getHydracoolSrpRow['id']] = $getHydracoolSrpRow['serial_number'];
			}

			$response = [
				'success' => 'true',
				'message' => 'SRP found',
				'serial_number'=>$serialNumberArray
			];

			return response()->json($response, $this->successStatus);
		}else{
			$response = [
				'success' => 'fail',
				'message' => 'no any active treatment center found'
			];
			return response()->json($response, 404);
		}
  }

	/**
	* Add hydracool Srp and units for non ema
	* @return json
	*/
	public function addNonEmaHydracoolSrp(Request $request){

		$userInput = array();
		$requestData = $request->all();
		$input =  $requestData['formData'];
		$validator = Validator::make($input, [
			'hydracool_srp_id' =>['required','numeric'],
			'treatment_center_id' =>['required','numeric'],
			'srp_units' =>['required'],
			'manufacturer_name' =>['required'],
			'manufacturing_date' =>['required'],
			'sale_date' =>['required'],
			]);

			if ($validator->fails()) {
				return response()->json(['error'=>$validator->errors()], 401);
			}else{
				$hydraCoolSrpId = $input['hydracool_srp_id'];
				$getHydraCoolSrpData = HydraCoolSrp::find($hydraCoolSrpId);
				if(!empty($getHydraCoolSrpData)){
							$getPrevisiousTreatmentcentreId = $getHydraCoolSrpData['fk_treatment_centers_id'];
								/* Get treatment centre name by Id */
								$getOldTreatmentCentreData = TreatmentCenter::getTreatmentCenterDetail($getPrevisiousTreatmentcentreId);
						/* Get treatment centre name by Id */
							$getOldCompanyName = '';
							if(!empty($getOldTreatmentCentreData[0])){
									$getOldCompanyName = ucfirst($getOldTreatmentCentreData[0]->full_company_name);
							}

					/*delete this hydracool srp and units which is already assign to previous any treatment center*/
							HydraCoolSrp::where('id', $hydraCoolSrpId)->update(['status' => 0]);
							HydraCoolSrpUnits::where('fk_hydracool_srp_id',$hydraCoolSrpId)->update(['status' => 0]);
					/*delete this hydracool srp and units which is already assign to previous any treatment center*/

					$treatmentCenterId = $input['treatment_center_id'];
					/*assign new treatment center to this srp serial number*/
					$hyracoolSrp = new HydraCoolSrp;
					$hyracoolSrp->fk_treatment_centers_id = $treatmentCenterId;
					$hyracoolSrp->serial_number = $getHydraCoolSrpData['serial_number'];
					$hyracoolSrp->is_demo = $getHydraCoolSrpData['is_demo'];
					$hyracoolSrp->created_by = Auth::user()->id;
					$hyracoolSrp->manufacturer_name = $input['manufacturer_name'];
					$hyracoolSrp->manufacturing_date = date('Y-m-d', strtotime($input['manufacturing_date']));
					$hyracoolSrp->sale_date = date('Y-m-d', strtotime($input['sale_date']));
					$hyracoolSrp->ip_address = request()->ip();
					$hyracoolSrp->save();
					$addedHydraCoolSrpID = $hyracoolSrp->id;
					/*assign new treatment center to this srp serial number*/

						/* Get treatment centre name by Id */
								$getTreatmentCentreData = TreatmentCenter::getTreatmentCenterDetail($treatmentCenterId);
						/* Get treatment centre name by Id */

						$getCompanyName = '';
						if(!empty($getTreatmentCentreData[0])){
								$getCompanyName = ucfirst($getTreatmentCentreData[0]->full_company_name);
						}
						$usercompanyName = getUserCompanyName(Auth::user());

						/* Merge company name and Srial number in order to show serial number in  audit log*/
								$companyName = $usercompanyName."/".$input['serial_number'];
						/* Merge company name and Srial number in order to show serial number in  audit log*/

					$moduleName = 'HydraCool SRP Device';
					$moduleActivity = 'De-allocated HydraCool SRP Device';
					// $description = "HydraCool SRP unit serial number ".$input['serial_number']." removed from Treatment centre ".$getOldCompanyName." and added to new Treatment centre ".$getCompanyName.".";
					$description = "HydraCool SRP unit ".$input['serial_number']." has been de-allocated from Treatment Centre ".$getOldCompanyName ." and allocated to Treatment Centre ".$getCompanyName;

					/*Add action in audit log*/
							captureAuditLog($moduleName,$moduleActivity,$description,$input,$companyName);
					/*Add action in audit log*/

					/*assing previous srp unit to current srp*/
					$getPreviousHydracoolSrpUnits = HydraCoolSrpUnits::select('title')
																													->where('fk_hydracool_srp_id',$hydraCoolSrpId)
																													->get()
																													->toArray();

					$getPreviousHydracoolSrpUnitsTitles = array_column($getPreviousHydracoolSrpUnits,'title');
					if(!empty($getPreviousHydracoolSrpUnitsTitles)){
						foreach ($getPreviousHydracoolSrpUnitsTitles as $value) {
							$UnitsRecord[] = [
								'fk_hydracool_srp_id' => $addedHydraCoolSrpID,
								'title' => $value,
								'ip_address'=>request()->ip()
							];
						}
						HydraCoolSrpUnits::insert($UnitsRecord);
					}

					/*assing previous srp unit to current srp*/
					if($hyracoolSrp){
						$message = 'HydraCool SRP Device added successfully';
						$response = [
							'success' => 'true',
							'message' => $message,
							'hydracool_srp_id'=>$addedHydraCoolSrpID
						];
						return response()->json($response, $this->successStatus);
					}else{
						$response = [
							'success' => 'fail',
							'message' => 'Something wrong please try again letter'
						];
					return response()->json($response, 200);
				}
			}else{
				$response = [
					'success' => 'fail',
					'message' => 'No HydraCool SRP Device found'
				];
				return response()->json($response, 404);
			}
		}

	}

	/**
  * Get the hydracool srp units by serial number
  * @return json
  */
  public function getSrpUnitsbySerialNumber(Request $request){

		$validator = Validator::make($request->all(), [
			'serial_number' =>['required']
		]);

		$getSerialNumber = $request->input('serial_number');

		/* check serial number is exists */
		$getHydraCoolSrpListData = HydraCoolSrp::getHydraCoolSrpSerialNumber($getSerialNumber);
		/* check serial number is exists */

		if(!empty($getHydraCoolSrpListData[0])){
			$message = 'HydraCool SRP list found';
			$response = [
				'success' => 'true',
				'message' => $message,
				'units_list'=>json_decode($getHydraCoolSrpListData[0]['title']),
				'hydracoolsrp_id'=>$getHydraCoolSrpListData[0]['hydracoolsrp_id'],
				'handset_id'=>$getHydraCoolSrpListData[0]['handset_id']
			];

		}else{
			$checkIsSerialNumberExists = HydraCoolSrp::checkSerialNumberUnique($getSerialNumber);

			if($checkIsSerialNumberExists!=''){
				$message = 'Serial number already exists';
				$response = [
					'success' => 'fail',
					'message' => $message,
					'is_exists'=>1
				];
			}else{
				$message = 'No HydraCool SRP Device available with this serial number';
				$response = [
					'success' => 'fail',
					'message' => $message,
				];
			}
		}
		return response()->json($response, $this->successStatus);
	}


	/**
	* Get the hydracool srp and units by hydreacool srp id
	* @return json
	*/
  public function getHydracoolSrpDetails(Request $request){

		$validator = Validator::make($request->all(), [
			'hydracool_srp_id' =>['required','numeric']
			]);

			$hydraCoolSrpId = $request->input('hydracool_srp_id');

			/* get hydracool srp unit list */
			$getHydraCoolSrpListData = HydraCoolSrp::getHydracoolSrpUnitDetails($hydraCoolSrpId);
			/* get hydracool srp unit list */

			if(!empty($getHydraCoolSrpListData)){
				$message = 'hydraCool srp details';
				$response = [
					'success' => 'true',
					'message' => $message,
					'serial_number'=>$getHydraCoolSrpListData['serial_number'],
					'units_list'=>json_decode($getHydraCoolSrpListData['title']),
					'hydracool_srp_id'=>$getHydraCoolSrpListData['hydracoolsrp_id'],
					'treatment_center_id'=>$getHydraCoolSrpListData['treatment_center_id'],
					'treatment_center_name'=>$getHydraCoolSrpListData['treatment_center_name'],
					'is_demo'=>$getHydraCoolSrpListData['is_demo'],
					'manufacturer_name'=>$getHydraCoolSrpListData['manufacturer_name'],
					'manufacturing_date'=>date('d-m-Y',strtotime($getHydraCoolSrpListData['manufacturing_date'])),
					'sale_date'=>date('d-m-Y',strtotime($getHydraCoolSrpListData['sale_date'])),
					'handset_id'=>$getHydraCoolSrpListData['handset_id']
				];
			}else{
				$message = 'No HydraCool SRP found';
				$response = [
					'success' => 'fail',
					'message' => $message,
				];
			}
			return response()->json($response, $this->successStatus);
	}

	/**
	*	suspend hydracool srp and units
	*   @return boolean success/error
	*/
	public function suspendHydracoolSrp(Request $request)
	{
		$userInput = array();
		$input = $request->all();
		$hydracoolSrpId = 	$input['hyracool_srp_id'];
		$validator = Validator::make($request->all(), [
			'hyracool_srp_id' =>['required','numeric'],
			]);

	   if ($validator->fails()) {
		   return response()->json(['error'=>$validator->errors()], 401);
	   }else{

			/*suspend hydracool srp and its units */
			$hydracoolsrp = HydraCoolSrp::find($hydracoolSrpId);
			if(isset($hydracoolsrp) && !empty($hydracoolsrp)){
				$hydracoolsrp->status=3;
				$hydracoolsrp->save();
				if($hydracoolsrp){

					/* update hydracool srp units */
					HydraCoolSrpUnits::where('fk_hydracool_srp_id',$hydracoolSrpId)->update(['status' => 3]);
					/* update hydracool srp units */

					$moduleName = 'HydraCool SRP';
					$moduleActivity = 'Suspended HydraCool SRP';
					$description = Auth::user()->name.' has suspended HydraCool SRP with'.$hydracoolsrp->serial_number." serial number.";

					/*Add action in audit log*/
					captureAuditLog($moduleName,$moduleActivity,$description,$input);
					/*Add action in audit log*/

					$message = 'HydraCool SRP suspended successfully';
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
				$message = 'HydraCool SRP not found';
				$response = [
					'success' => 'fail',
					'message' => $message,
				];
				return response()->json($response, 404);
			}
			/*suspend hydracool srp and its units */
		}
	}

   /**
   *	release hydracool srp and its units
   *   @return boolean success/error
   */
	public function releaseHydracoolSrp(Request $request)
	{

		$userInput = array();
		$input = $request->all();
		$hydracoolSrpId = $input['hyracool_srp_id'];
		$validator = Validator::make($request->all(), [
			'hyracool_srp_id' =>['required','numeric'],
			]);

			if ($validator->fails()) {
				return response()->json(['error'=>$validator->errors()], 401);
			}else{

				/*release hydracool srp and its units*/
				$hydracoolsrp = HydraCoolSrp::find($hydracoolSrpId);
				if(isset($hydracoolsrp) && !empty($hydracoolsrp)){
					$hydracoolsrp->status=1;
					$hydracoolsrp->save();
					if($hydracoolsrp){

						/* update hydracool srp units */
						HydraCoolSrpUnits::where('fk_hydracool_srp_id',$hydracoolSrpId)->update(['status' => 1]);
						/* update hydracool srp units */

						$moduleName = 'HydraCool SRP';
						$moduleActivity = 'Release HydraCool SRP';
						$description = Auth::user()->name.' has released HydraCool SRP with'.$hydracoolsrp->serial_number.".";

						/*Add action in audit log*/
						captureAuditLog($moduleName,$moduleActivity,$description,$input);
						/*Add action in audit log*/

						$message = 'HydraCool SRP released successfully';
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
					$message = 'HydraCool SRP not found';
					$response = [
						'success' => 'fail',
						'message' => $message,
					];
					return response()->json($response, 404);
				}
				/*release hydracool srp and its units*/
			}
	}


  /**
	*	delete hydracool rsr
	*   @return boolean success/error
	*/
	public function deleteHydracoolSrp(Request $request)
	{
		$userInput = array();
		$input = $request->all();
		$hydracoolSrpId = 	$input['hyracool_srp_id'];

		$validator = Validator::make($request->all(), [
			'hyracool_srp_id' =>['required','numeric'],
			]);

			if ($validator->fails()) {
				return response()->json(['error'=>$validator->errors()], 401);
			}else{

				/*delete hydracool srp and its units*/
				$hydracoolsrp = HydraCoolSrp::find($hydracoolSrpId);
				if(isset($hydracoolsrp) && !empty($hydracoolsrp)){
					$getTreatmentCentreId = $hydracoolsrp->fk_treatment_centers_id;
					$hydracoolsrp->status=2;
					$hydracoolsrp->save();
					if($hydracoolsrp){

						/* update hydracool srp units */
						HydraCoolSrpUnits::where('fk_hydracool_srp_id',$hydracoolSrpId)->update(['status' => 2]);
						/* update hydracool srp units */

							/* Get treatment centre name by Id */
							$getTreatmentCentreData = TreatmentCenter::getTreatmentCenterDetail($getTreatmentCentreId);
							/* Get treatment centre name by Id */

							$getCompanyName = '';
							if(!empty($getTreatmentCentreData[0])){
									$getCompanyName = ucfirst($getTreatmentCentreData[0]->full_company_name);
							}
							$usercompanyName = getUserCompanyName(Auth::user());
							/* Merge company name and Srial number in order to show serial number in  audit log*/
									$companyName = $usercompanyName."/".$hydracoolsrp->serial_number;
							/* Merge company name and Srial number in order to show serial number in  audit log*/

						$moduleName = 'HydraCool SRP';
						$moduleActivity = 'Deleted HydraCool SRP';
						//$description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has Deleted HydraCool SRP Device with '.$hydracoolsrp->serial_number." Serial number";
						// $description = "HydraCool SRP unit serial number ".$hydracoolsrp->serial_number." deleted from Treatment centre ".$getCompanyName.".";
						$description = "HydraCool SRP Device ".$hydracoolsrp->serial_number." Serial number has been deleted";
						$requestData = array('hydracool_srp_id'=>$hydracoolSrpId);

						/*Add action in audit log*/
						captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
						/*Add action in audit log*/

						$message = 'HydraCool SRP Device delete successfully';
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
					$message = 'HydraCool SRP Device not found';
					$response = [
						'success' => 'fail',
						'message' => $message,
					];
					return response()->json($response, 404);
				}
				/*delete hydracool srp and its units*/
			}
	}
	/**
	*	check handset value unique
	* @return boolean success/error
	*/
	public function checkHandsetSerialNumberValueUnique(Request $request)
	{
		$getHandSetValue = $request->get('handset_value');
		$exceptId = $request->get('exceptId');
		if($getHandSetValue){
				$getCount =HydraCoolSrpUnits::where(function ($query) use($getHandSetValue){
					$query->where(DB::raw("lower(json_unquote(json_extract(title, '$.AquaB_unit')))"),strtolower( $getHandSetValue ));
					$query->orwhere(DB::raw("lower(json_unquote(json_extract(title, '$.MicroT_unit')))"),strtolower( $getHandSetValue ));
					$query->orwhere(DB::raw("lower(json_unquote(json_extract(title, '$.UltraB_unit')))"),strtolower( $getHandSetValue ));
					$query->orwhere(DB::raw("lower(json_unquote(json_extract(title, '$.VibroX_unit')))"),strtolower( $getHandSetValue ));
					$query->orwhere(DB::raw("lower(json_unquote(json_extract(title, '$.Collagen_unit')))"),strtolower( $getHandSetValue ));
					$query->orwhere(DB::raw("lower(json_unquote(json_extract(title, '$.Hub_Board_unit')))"),strtolower( $getHandSetValue ));

				});
				if(isset($exceptId) && !empty($exceptId)){
					$getCount->where('id','!=',$exceptId);
				}
				$getCount->whereIn('status',[1,3]);

				$getCount = $getCount->count();
				if($getCount > 0) {
					return response()->json(false, 200);
				}else{
					return response()->json(true, 200);
				}
		}else{
			$message = 'Please enter handset value';
			$response = [
					'success' => 'fail',
					'message' => $message,
			];
			return response()->json(false, 200);
		}
	}

	public function uniqueSerialNumber(Request $request){
		if($request->id){
			$hydracool=HydraCoolSrp::where('serial_number',$request->srp_serial_number)
														 ->where('id','!=',$request->id)
														 ->whereNotIn('status',[2,0])
		 	                       ->first();
		}else{
				$hydracool=HydraCoolSrp::where('serial_number',$request->srp_serial_number)
															 ->whereNotIn('status',[2,0])
															 ->first();
		}
		if($hydracool){
			return response()->json(false);
		}
		else{
			return response()->json(true);
		}
	}
	public function getAllHydraCoolSrp(Request $request){
		$getHydraCoolSrpObj = HydraCoolSrp::getHydraCoolSrpSerialNumber();
		$serialNumberList  = array();
		if($getHydraCoolSrpObj){
				foreach($getHydraCoolSrpObj as $getHydraCoolSrp){
					$serialNumberList[] = $getHydraCoolSrp->serial_number;
				}
			$message = 'Serial number found';
			$response = [
				'success' => 'success',
				'message' => $message,
				'data'=>$serialNumberList
			];
			return response()->json($response, 200);
		}
		else{
			$message = 'No data found';
			$response = [
				'success' => 'fail',
				'message' => $message,
			];
			return response()->json($response,200);
		}

	}

}
