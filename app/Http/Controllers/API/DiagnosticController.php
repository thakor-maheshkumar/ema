<?php

namespace App\Http\Controllers\API;

use App\DynamoBootDbModel;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use BaoPham\DynamoDb\Facades\DynamoDb;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDynamicEmail;
use \Illuminate\Support\Facades\URL;
use DateTime;
use App\HydraCoolSrp;
use App\TreatmentBootJson;
use App\TreatmentCenter;
use App\Distributor;
use App\TreatmentBootJsonComment;
use App\ErrorCode;
use DB;

class DiagnosticController extends Controller
{

	public $successStatus = 200;
	/**
	* get treatment center filelist
	*/
	public function diagnosticData(Request $request){
		if($request->wantsjson()){

			$authUser = Auth::user();
			$allSerialNumber = array();
			$getDiagnosticBootData  = array();
			if($authUser->hasRole(['distributor principal']) ||  $authUser->hasRole(['distributor sales'])){
				$getDistributorCompanyData = getDistributorCompanyDataById();
				$getAllAssignedTreatmetnCetre = TreatmentCenter::select('id')->where('distributors',$getDistributorCompanyData->distributor_company_id)->get()->toArray();
				if(!empty($getAllAssignedTreatmetnCetre)){
					$allTreatmentIds =array_column($getAllAssignedTreatmetnCetre,'id');

					if(!empty($allTreatmentIds)){
						$getAllHydraCoolSARPOfTreatmentCentre = HydraCoolSrp::select('serial_number')->whereIn('fk_treatment_centers_id',$getAllAssignedTreatmetnCetre)->get()->toArray();
						if(!empty($getAllHydraCoolSARPOfTreatmentCentre)){
								$allSerialNumber =array_column($getAllHydraCoolSARPOfTreatmentCentre,'serial_number');
								$getDiagnosticBootData = TreatmentBootJson::orderBy('id', 'desc')->where('sta','CR')->whereIn('DSN',$allSerialNumber);
						}
					}
				}
			}
			if($authUser->hasRole(['treatment centre manager'])){
					$getAllHydraCoolSARPOfTreatmentCentre = HydraCoolSrp::select('serial_number')->whereIn('fk_treatment_centers_id',$getAllAssignedTreatmetnCetre)->get()->toArray();
					if(!empty($getAllHydraCoolSARPOfTreatmentCentre)){
						$allSerialNumber =array_column($getAllHydraCoolSARPOfTreatmentCentre,'serial_number');
						$getDiagnosticBootData = TreatmentBootJson::orderBy('id', 'desc')->where('sta','CR')->whereIn('DSN',$allSerialNumber);
					}
			}

			if($authUser->hasRole(['system administrator']) || $authUser->hasRole(['ema analyst'])){
						$getDiagnosticBootData = TreatmentBootJson::orderBy('id', 'desc')->where('sta','CR');
			}

					return Datatables::of($getDiagnosticBootData)
					->editColumn('UTI_date', function ($getDiagnosticBootData){
						return date('d-m-Y', strtotime($getDiagnosticBootData->UTI));
					})
					->editColumn('UTI_time', function ($getDiagnosticBootData){
						return date('h:i:s', strtotime($getDiagnosticBootData->UTI));
					})

					->addColumn('treatment_center_name',function($getDiagnosticBootData){
							$getTreatmentCentreData = TreatmentCenter::getTreatmentCentreDetails($getDiagnosticBootData->DSN);
							$treatmentcentrename = '--';
							if(!empty($getTreatmentCentreData)){
								$treatmentcentrename = $getTreatmentCentreData[0]['full_company_name'];
							}
						return $treatmentcentrename;
					})

					->editColumn('error_type', function ($getDiagnosticBootData){
						return $getDiagnosticBootData->STA = jsonDataShortCode($getDiagnosticBootData->STA,'treatment_status');
					})

					->addColumn('description', function ($getDiagnosticBootData){
						$getWaeData = json_decode($getDiagnosticBootData->WAE);

						if(!empty($getWaeData)){
							$allError = array();
							foreach ($getWaeData as $key => $getWae) {
								$allError[] = ErrorCode::getCodeValueByCode($getWae[1]);
							}
							return $getDiagnosticBootData->WAE = implode(',', $allError);
						}else{
							return $getDiagnosticBootData->WAE = '';
						}

					})

					->addColumn('comment', function ($getDiagnosticBootData){
						$getLastCommentData = TreatmentBootJsonComment::select('comment')->where('boot_json_id',$getDiagnosticBootData->id)->orderBy('id','desc')->first();
						if(!empty($getLastCommentData)){
							return $getLastCommentData->comment;
						}else{
							return '--';
						}

					})
					->addColumn('action', function ($getDiagnosticBootData) {
						$view = '<a href="javascript:;" class="viewDiagnosticData" data-getTreatmentcentreData='.$getDiagnosticBootData->id.' title="View User" data-toggle="tooltip"><i class="far fa-eye"></i></a>';
						return $view;

		})->rawColumns(['Traffic Light','action'])->toJson();
		}else{
			$isAdd = '0';
			$getTreatmentCenterId='';
			return view('diagnostic.diagnostic-list',compact('getTreatmentCenterId','isAdd'));
		}
	}

	public function diagnosticDetails(Request $request){
		if(isset($request->pid) && !empty($request->pid)){
			$getTreatmentcentreData = DynamoBootDbModel::where("pid",$request->pid)->first();

			if($getTreatmentcentreData->count() > 0){
				$data['success'] = '1';
				$data['getTreatmentcentreData'] = $getTreatmentcentreData;
			}else{
				$data['success'] = '0';
				$data['message'] = 'No Record Found';
			}
		}else{
			$data['success'] = '0';
			$data['message'] = 'Please enter the log id';
		}
		echo json_encode($data);
	}

	public function diagnosticDataDashboard(Request $request){
		$getTreatmentcentreData = TreatmentBootJson::orderBy('id', 'desc')->where('sta','CR')->take(5)->get();
		return Datatables::of($getTreatmentcentreData)
		->editColumn('UTI_date', function ($getTreatmentcentreData){
			return date('d-m-Y', strtotime($getTreatmentcentreData->UTI));
		})
		->editColumn('UTI_time', function ($getTreatmentcentreData){
			return date('h:i:s', strtotime($getTreatmentcentreData->UTI));
		})
		->editColumn('STA', function ($getTreatmentcentreData){
			return $getTreatmentcentreData->STA = jsonDataShortCode($getTreatmentcentreData->STA,'treatment_status');
		})
		->addColumn('Traffic Light',function($getTreatmentcentreData){
			if($getTreatmentcentreData->status=="Action Completed"){
				$icon= asset("images/green_dot.png");
			}else if($getTreatmentcentreData->status=="Action Progress"){
				$icon= asset("images/yellow_dot.png");
			}else{
				$icon= asset("images/red_dot.png");
			}

			return '<img src='.$icon.' border="0" width="15px" style="text-align: center;" class="img-rounded" align="center" />';
		})
		->addColumn('action', function ($getTreatmentcentreData) {
			$view = '<a href="javascript:;" class="viewDiagnosticData" data-getTreatmentcentreData='.$getTreatmentcentreData->id.' title="View User" data-toggle="tooltip"><i class="far fa-eye"></i></a>';
			return $view;

		})->rawColumns(['Traffic Light','action'])->toJson();
	}

	public function viewDiagnosticData($id){
		$diagnosticData = TreatmentBootJson::where('id',$id)->first();

		if($diagnosticData){
			$getTreatmentCentreData = TreatmentCenter::getTreatmentCentreDetails($diagnosticData->DSN);
			$treatmentCenterName='';
			$distributorName='';
			if(!empty($getTreatmentCentreData)){

				$treatmentCenterName = $getTreatmentCentreData[0]['full_company_name'];
				if(!empty($getTreatmentCentreData[0]['distributors'])){
					$getDistributorData = Distributor::find($getTreatmentCentreData[0]['distributors']);
					$distributorName =$getDistributorData->full_company_name;
				}
			}

			$diagnostic_data =array();
			$diagnostic_data['dsn'] =$diagnosticData->DSN;
			$diagnostic_data['uti_date'] = date('d-m-Y', strtotime($diagnosticData->UTI));
			$diagnostic_data['uti_time'] = date('h:i:s', strtotime($diagnosticData->UTI));
			$diagnostic_data['treatment_centre_name'] = $treatmentCenterName;
			$diagnostic_data['distributor_name'] = $distributorName;
			$diagnostic_data['alarm'] = jsonDataShortCode('CR','treatment_status');
			$diagnostic_data['status'] = $diagnosticData->status;
				/* Get all comments list */
				$getBootJsonCommentList = $this->getDiagnosticCommentsList($id);
				/* Get all comments list */
			$diagnostic_data['diagnostic_comment_list'] =$getBootJsonCommentList;
			return response()->json(['success'=>true,'diagnostic_data'=>$diagnostic_data,'message'=>'Diagnostic Data Found']);
		}else{
			return response()->json(['success'=>false,'message'=>'Diagnostic Data Not Found']);
		}
	}

	public function addDiagnosticComment(Request $request){
		$userInput = array();
		$requestData = $request->all();
		$validator = Validator::make($requestData, [
			'diagnostic_status' => ['required'],
			'diagnostic_comment' =>['required'],
			'json_id' =>['required']
			]);

			if ($validator->fails()) {
				return response()->json(['errors'=>$validator->errors()], 422);
			}else{
				$treatmentbootjsoncomment = new TreatmentBootJsonComment;
				$treatmentbootjsoncomment->boot_json_id=$requestData['json_id'];
				$treatmentbootjsoncomment->comment=$requestData['diagnostic_comment'];
				$treatmentbootjsoncomment->user_id= Auth::id();
				if($treatmentbootjsoncomment->save()){

					/* Get all comments list */
							$getBootJsonCommentList = $this->getDiagnosticCommentsList($requestData['json_id']);
					/* Get all comments list */

					/* update treatment boot json status */
							TreatmentBootJson::where('id',$requestData['json_id'])->update(['status' => $requestData['diagnostic_status']]);
					/* update treatment boot json status */

					$message = 'Comment Added successfully';
					$response = [
						'success' => true,
						'message' => $message,
						'diagnostic_comment_list'=>$getBootJsonCommentList
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
			}
		}

		public function getDiagnosticCommentsList($bootJsonId){
				$getBootJsonCommentsList = TreatmentBootJsonComment::join('users','users.id','=','treatment_boot_json_comment.user_id')
																													 ->select('treatment_boot_json_comment.*','users.name',DB::raw('DATE_FORMAT(treatment_boot_json_comment.created_at, "%d-%m-%Y %H:%i:%s") as added_date'))
																													 ->where('boot_json_id',$bootJsonId)->get()->toArray();
				return $getBootJsonCommentsList;
		}
	}
