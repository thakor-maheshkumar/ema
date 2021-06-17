<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\TreatmentCenter;
use App\CosmeticDeliveries;
use Auth;
use Yajra\DataTables\Facades\DataTables;
use App\CoreSetting;

class CosmeticDeliveriesController extends Controller
{
    //
    public $successStatus = 200;

    public function getCosmeticDeliveries(Request $request,$id){

        if($request->ajax()){
			$searchData =$request->get('search_data');
			if(isset($searchData) && !empty($searchData)){
				$gettreatmentcentredata = CosmeticDeliveries::getCosmeticList($searchData,$id);
				$data = makeNumericArray($gettreatmentcentredata);
				return $data;
			}else{
                $getCosmeticData = CosmeticDeliveries::getCosmeticList(null,$id,null);
                return Datatables::of($getCosmeticData)
                ->addColumn('action', function ($getCosmeticRow) {
                    $edit = $view = $delete = '';
                    $authUser = Auth::user();

                    if($authUser->hasRole(['system administrator','distributor principal','distributor service','distributor sales'])){
                        $edit = '<a href="javascript:;" class="editcosmeticdata" data-cosmeticId="'.$getCosmeticRow->id.'" title="Edit" ><i class="far fa-edit"></i></a>';
                    }

                    if($authUser->hasRole(['system administrator','ema analyst','ema service support','distributor principal','distributor service','distributor sales'])){
                        $view = '<a href="#" title="View" class="viewcosmeticdata" data-cosmeticId="'.$getCosmeticRow->id.'"><i class="far fa-eye"></i></a>';
                    }

                    if($authUser->hasRole(['system administrator','distributor principal','distributor service','distributor sales'])){
                        $delete='<a href="javascript:;" class="deletecosmeticdata"  data-cosmeticId="'.$getCosmeticRow->id.'" title="Delete"><i class="far fa-trash-alt"></i></a>';
                    }

                    return $edit.$view.$delete;
                })

                ->editColumn('solution_bottle_pack', function ($getCosmeticData){
                    $solution_bottle_pack = json_decode($getCosmeticData->solution_bottle_pack);
                    return $getCosmeticData->solution_bottle_pack = $solution_bottle_pack->order_value ? $solution_bottle_pack->order_value : '0';
                })

                ->editColumn('solution_1', function ($getCosmeticData){
                    $solution_1 = json_decode($getCosmeticData->solution_1);
                    return $getCosmeticData->solution_1 = $solution_1->order_value ? $solution_1->order_value : '0';
                })

                ->editColumn('solution_2', function ($getCosmeticData){
                    $solution_2 = json_decode($getCosmeticData->solution_2);
                    return $getCosmeticData->solution_2 = $solution_2->order_value ? $solution_2->order_value : '0';
                })

                ->editColumn('solution_3', function ($getCosmeticData){
                    $solution_3 = json_decode($getCosmeticData->solution_3);
                    return $getCosmeticData->solution_3 = $solution_3->order_value ? $solution_3->order_value : '0';
                })

                ->editColumn('solution_4', function ($getCosmeticData){
                    $solution_4 = json_decode($getCosmeticData->solution_4);
                    return $getCosmeticData->solution_4 = $solution_4->order_value ? $solution_4->order_value : '0';
                })

                ->editColumn('cosmetic_fresh_pack', function ($getCosmeticData){
                    $cosmetic_fresh_pack = json_decode($getCosmeticData->cosmetic_fresh_pack);
                    return $getCosmeticData->cosmetic_fresh_pack = $cosmetic_fresh_pack->order_value ? $cosmetic_fresh_pack->order_value : '0';
                })

                ->editColumn('cosmetic_bright_pack', function ($getCosmeticData){
                    $cosmetic_bright_pack = json_decode($getCosmeticData->cosmetic_bright_pack);
                    return $getCosmeticData->cosmetic_bright_pack = $cosmetic_bright_pack->order_value ? $cosmetic_bright_pack->order_value : '0';
                })

                ->editColumn('booster_packs', function ($getCosmeticData){
                    $booster_packs = json_decode($getCosmeticData->booster_packs);
                    return $getCosmeticData->booster_packs = $booster_packs->order_value ? $booster_packs->order_value : '0';
                })

                ->editColumn('aquaB_tips', function ($getCosmeticData){
                    $aquaB_tips = json_decode($getCosmeticData->aquaB_tips);
                    return $getCosmeticData->aquaB_tips = $aquaB_tips->order_value ? $aquaB_tips->order_value : '0';
                })

                ->editColumn('delivery_date', function ($getCosmeticData){
                    return $getCosmeticData->delivery_date =  date('d-m-Y',strtotime($getCosmeticData->delivery_date));
                })

                ->filterColumn('delivery_date', function($getCosmeticData, $keyword) {
                    $from_date = date('Y-m-d',strtotime(str_replace('\-','-', $keyword)));
                    $getCosmeticData->whereRaw("date(delivery_date) LIKE '%".$from_date."%'");

                })

                ->make(true);
            }
        }
    }

    /* Add cosmetic solution data */
    public function addCosmeticData(Request $request){
        $userInput = array();
        $requestData = $request->all();
        $inputData = $requestData['formData'];
        $validator = Validator::make($inputData, [
            'delivery_date' =>['required','max:255'],
            'treatment_center_id' =>['required'],
            'solution_bottle_pack'=>'nullable|numeric',
            'solution_1'=>'nullable|numeric',
            'solution_2'=>'nullable|numeric',
            'solution_3'=>'nullable|numeric',
            'solution_4'=>'nullable|numeric',
            'cosmetic_fresh_pack'=>'nullable|numeric',
            'cosmetic_bright_pack'=>'nullable|numeric',
            'booster_packs'=>'nullable|numeric',
            'aquaB_tips'=>'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }else{
            $get4bottlePackValue = CoreSetting::where('name','solution_bottle_pack')->first()->value;
            $getCosmeticPackValue = CoreSetting::where('name','cosmetic_fresh_pack')->first()->value;
            $getBoostrePackValue = CoreSetting::where('name','booster_packs')->first()->value;
            $getAquaBPackValue = CoreSetting::where('name','aquaB_tips')->first()->value;


            /* Add Cosmetic data */
            $cosmeticDeliveries = new CosmeticDeliveries;
            $cosmeticDeliveries->fk_treatment_centers_id =$inputData['treatment_center_id'];
            $cosmeticDeliveries->solution_bottle_pack =json_encode(array('order_value'=>$inputData['solution_bottle_pack'],'total_value'=>$inputData['solution_bottle_pack']*$get4bottlePackValue));
            $cosmeticDeliveries->solution_1 =json_encode(array('order_value'=>$inputData['solution_1'],'total_value'=>'1'));
            $cosmeticDeliveries->solution_2 =json_encode(array('order_value'=>$inputData['solution_2'],'total_value'=>'1'));
            $cosmeticDeliveries->solution_3 =json_encode(array('order_value'=>$inputData['solution_3'],'total_value'=>'1'));
            $cosmeticDeliveries->solution_4 =json_encode(array('order_value'=>$inputData['solution_4'],'total_value'=>'1'));
            $cosmeticDeliveries->cosmetic_fresh_pack =json_encode(array('order_value'=>$inputData['cosmetic_fresh_pack'],'total_value'=>$inputData['cosmetic_fresh_pack']*$getCosmeticPackValue));
            $cosmeticDeliveries->cosmetic_bright_pack =json_encode(array('order_value'=>$inputData['cosmetic_bright_pack'],'total_value'=>$inputData['cosmetic_bright_pack']*$getCosmeticPackValue));
            $cosmeticDeliveries->booster_packs =json_encode(array('order_value'=>$inputData['booster_packs'],'total_value'=>$inputData['booster_packs']*$getBoostrePackValue));
            $cosmeticDeliveries->aquaB_tips =json_encode(array('order_value'=>$inputData['aquaB_tips'],'total_value'=>$inputData['aquaB_tips']*$getAquaBPackValue));
            $cosmeticDeliveries->delivery_date =date('Y-m-d', strtotime($inputData['delivery_date']));
            $cosmeticDeliveries->created_by=Auth::user()->id;
            $cosmeticDeliveries->ip_address=request()->ip();

            if($cosmeticDeliveries->save()){

                /* Get treatment centre name by Id */
                $getTreatmentCentreData = TreatmentCenter::getTreatmentCenterDetail($inputData['treatment_center_id']);
                /* Get treatment centre name by Id */
                $getCompanyName = '';
                if(!empty($getTreatmentCentreData[0])){
                    $getCompanyName = ucfirst($getTreatmentCentreData[0]->full_company_name);
                }
                $moduleName = 'Cosmetic Deliveries';
        		$moduleActivity = 'Added Cosmetic Deliveries';
                // $description =  ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has added Cosmetic Deliveries for '.$getCompanyName.' Treatment centre.';
                $description =  "New Cosmetic Delivery entered for ".$getCompanyName." Treatment Centre";
                $usercompanyName = getUserCompanyName(Auth::user());
                /*Add action in audit log*/
                captureAuditLog($moduleName,$moduleActivity,$description,$inputData,$usercompanyName);
                /*Add action in audit log*/

                $message = 'Cosmetic Deliveries added successfully';

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
            /* Add Cosmetic data */
        }
    }
    /* view cosmetic solution data */
    public function getCosmeticDeliveriesDetails(Request $request){
        $getCosmeticDataId = $request->input('cosmetic_data_id');
        $treatmentcentreID = $request->input('treatment_center_id');
        $validator = Validator::make($request->all(), [
            'treatment_center_id' =>['required','numeric'],
            'cosmetic_data_id' =>['required','numeric'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }else{

            $getCosmetiData = CosmeticDeliveries::getCosmeticListDetails($getCosmeticDataId);
            $getCosmeticDeliveriesRow = array();
            if(!empty($getCosmetiData)){
                $solution_bottle_pack = json_decode($getCosmetiData['solution_bottle_pack']);
                $solution_1 = json_decode($getCosmetiData['solution_1']);
                $solution_2 = json_decode($getCosmetiData['solution_2']);
                $solution_3 = json_decode($getCosmetiData['solution_3']);
                $solution_4 = json_decode($getCosmetiData['solution_4']);
                $cosmetic_fresh_pack = json_decode($getCosmetiData['cosmetic_fresh_pack']);
                $cosmetic_bright_pack = json_decode($getCosmetiData['cosmetic_bright_pack']);
                $booster_packs = json_decode($getCosmetiData['booster_packs']);
                $aquaB_tips = json_decode($getCosmetiData['aquaB_tips']);
                $getCosmeticDeliveriesRow['solution_bottle_pack'] = $solution_bottle_pack->order_value ? $solution_bottle_pack->order_value : '0';
                $getCosmeticDeliveriesRow['solution_1'] = $solution_1->order_value ? $solution_1->order_value  : '0';
                $getCosmeticDeliveriesRow['solution_2'] = $solution_2->order_value ? $solution_2->order_value : '0';
                $getCosmeticDeliveriesRow['solution_3'] = $solution_3->order_value ? $solution_3->order_value : '0';
                $getCosmeticDeliveriesRow['solution_4'] = $solution_4->order_value ? $solution_4->order_value : '0';
                $getCosmeticDeliveriesRow['cosmetic_fresh_pack'] = $cosmetic_fresh_pack->order_value ? $cosmetic_fresh_pack->order_value : '0';
                $getCosmeticDeliveriesRow['cosmetic_bright_pack'] = $cosmetic_bright_pack->order_value ? $cosmetic_bright_pack->order_value : '0';
                $getCosmeticDeliveriesRow['booster_packs'] = $booster_packs->order_value ? $booster_packs->order_value : '0';
                $getCosmeticDeliveriesRow['aquaB_tips'] = $aquaB_tips->order_value ? $aquaB_tips->order_value : '0';
                $getCosmeticDeliveriesRow['delivery_date'] = date('d-m-Y',strtotime($getCosmetiData['delivery_date']));
                $getCosmeticDeliveriesRow['id'] = $getCosmetiData['id'];
            }
            if(!empty($getCosmeticDeliveriesRow)){
                $message = 'Cosmetic Deliveries found';
                $response = [
                    'success' => true,
                    'message' => $message,
                    'cosmetic_details'=>$getCosmeticDeliveriesRow
                ];
            }else{
                $message = 'Cosmetic Deliveries not found';
                $response = [
                    'success' => false,
                    'message' => $message,
                ];
            }
			return response()->json($response,$this->successStatus);
        }
    }

    /* update cosmetic solution data */
    public function updateCosmeticData(Request $request){
        $userInput = array();
        $requestData = $request->all();
        $inputData = $requestData['formData'];
        $validator = Validator::make($inputData, [
            'delivery_date' =>['required','max:255'],
            'treatment_center_id' =>['required'],
            'solution_bottle_pack'=>'nullable|numeric',
            'solution_1'=>'nullable|numeric',
            'solution_2'=>'nullable|numeric',
            'solution_3'=>'nullable|numeric',
            'solution_4'=>'nullable|numeric',
            'cosmetic_fresh_pack'=>'nullable|numeric',
            'cosmetic_bright_pack'=>'nullable|numeric',
            'booster_packs'=>'nullable|numeric',
            'aquaB_tips'=>'nullable|numeric',
            'cosmetic_id'=>['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }else{
            $get4bottlePackValue = CoreSetting::where('name','solution_bottle_pack')->first()->value;
            $getCosmeticPackValue = CoreSetting::where('name','cosmetic_fresh_pack')->first()->value;
            $getBoostrePackValue = CoreSetting::where('name','booster_packs')->first()->value;
            $getAquaBPackValue = CoreSetting::where('name','aquaB_tips')->first()->value;
            $cosmetic_id = $inputData['cosmetic_id'];
            $cosmetic = CosmeticDeliveries::findOrFail($cosmetic_id);

            /* Get original Data  before update*/
            $originalDataInput = array();
            $originalData = getOriginalData($cosmetic);
            if(!empty($originalData)){
                    $solution_bottle_pack = json_decode($originalData['solution_bottle_pack']);
                    $solution_1 = json_decode($originalData['solution_1']);
                    $solution_2 = json_decode($originalData['solution_2']);
                    $solution_3 = json_decode($originalData['solution_3']);
                    $solution_4 = json_decode($originalData['solution_4']);
                    $cosmetic_bright_pack = json_decode($originalData['cosmetic_bright_pack']);
                    $cosmetic_fresh_pack = json_decode($originalData['cosmetic_fresh_pack']);
                    $booster_packs = json_decode($originalData['booster_packs']);
                    $aquaB_tips = json_decode($originalData['aquaB_tips']);

                    $originalDataInput['solution_bottle_pack'] = $solution_bottle_pack->order_value ? $solution_bottle_pack->order_value : '0';
                    $originalDataInput['solution_1'] = $solution_1->order_value  ? $solution_1->order_value : '0';
                    $originalDataInput['solution_2'] = $solution_2->order_value  ? $solution_2->order_value : '0';
                    $originalDataInput['solution_3'] = $solution_3->order_value  ? $solution_3->order_value : '0';
                    $originalDataInput['solution_4'] = $solution_4->order_value  ? $solution_4->order_value : '0';
                    $originalDataInput['cosmetic_bright_pack'] = $cosmetic_bright_pack->order_value  ? $cosmetic_bright_pack->order_value : '0';
                    $originalDataInput['cosmetic_fresh_pack'] = $cosmetic_fresh_pack->order_value  ? $cosmetic_fresh_pack->order_value : '0';
                    $originalDataInput['booster_packs'] = $booster_packs->order_value  ? $booster_packs->order_value : '0';
                    $originalDataInput['aquaB_tips'] = $aquaB_tips->order_value  ? $aquaB_tips->order_value : '0';
                    $originalDataInput['aquaB_tips'] = $aquaB_tips->order_value  ? $aquaB_tips->order_value : '0';
                    $originalDataInput['delivery_date'] = date('d-m-Y',strtotime($originalData['delivery_date']));
                    $originalDataInput['treatment_center_id'] = $originalData['fk_treatment_centers_id'];
            }
            /* Get original Data  before update*/

            /* delete previous Cosmetic data */
            $deleteCosmeticDeliveries = CosmeticDeliveries::where('id',$cosmetic_id)->delete();
            /* delete previous Cosmetic data */

            $cosmeticDeliveries = new CosmeticDeliveries;
            $cosmeticDeliveries->fk_treatment_centers_id =$inputData['treatment_center_id'];
            $cosmeticDeliveries->solution_bottle_pack =json_encode(array('order_value'=>$inputData['solution_bottle_pack'],'total_value'=>$inputData['solution_bottle_pack']*$get4bottlePackValue));
            $cosmeticDeliveries->solution_1 =json_encode(array('order_value'=>$inputData['solution_1'],'total_value'=>'1'));
            $cosmeticDeliveries->solution_2 =json_encode(array('order_value'=>$inputData['solution_2'],'total_value'=>'1'));
            $cosmeticDeliveries->solution_3 =json_encode(array('order_value'=>$inputData['solution_3'],'total_value'=>'1'));
            $cosmeticDeliveries->solution_4 =json_encode(array('order_value'=>$inputData['solution_4'],'total_value'=>'1'));
            $cosmeticDeliveries->cosmetic_fresh_pack =json_encode(array('order_value'=>$inputData['cosmetic_fresh_pack'],'total_value'=>$inputData['cosmetic_fresh_pack']*$getCosmeticPackValue));
            $cosmeticDeliveries->cosmetic_bright_pack =json_encode(array('order_value'=>$inputData['cosmetic_bright_pack'],'total_value'=>$inputData['cosmetic_bright_pack']*$getCosmeticPackValue));
            $cosmeticDeliveries->booster_packs =json_encode(array('order_value'=>$inputData['booster_packs'],'total_value'=>$inputData['booster_packs']*$getBoostrePackValue));
            $cosmeticDeliveries->aquaB_tips =json_encode(array('order_value'=>$inputData['aquaB_tips'],'total_value'=>$inputData['aquaB_tips']*$getAquaBPackValue));
            $cosmeticDeliveries->delivery_date =date('Y-m-d', strtotime($inputData['delivery_date']));
            $cosmeticDeliveries->created_by=Auth::user()->id;
            $cosmeticDeliveries->ip_address=request()->ip();
            if($cosmeticDeliveries->save()){

                /* Get treatment centre name by Id */
                $getTreatmentCentreData = TreatmentCenter::getTreatmentCenterDetail($inputData['treatment_center_id']);
                /* Get treatment centre name by Id */
                $getCompanyName = '';
                if(!empty($getTreatmentCentreData[0])){
                    $getCompanyName = ucfirst($getTreatmentCentreData[0]->full_company_name);
                }
                $moduleName = 'Cosmetic Deliveries';
        		$moduleActivity = 'Updated Cosmetic Deliveries';
                // $description =  ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has updated Cosmetic Deliveries for '.$getCompanyName.' Treatment centre.';
                $description =  "Cosmetic Delivery amended for ".$getCompanyName." Treatment Centre";
                $usercompanyName = getUserCompanyName(Auth::user());

                /*Add action in audit log*/
                captureAuditLog($moduleName,$moduleActivity,$description,$originalDataInput,$usercompanyName);
                /*Add action in audit log*/

                $message = 'Cosmetic Deliveries updated successfully';

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
            /* Add Cosmetic data */
        }
    }

    /**
	*	delete cosmetic data
	*   @return boolean success/error
	*/
	public function deleteCosmeticData(Request $request){

        $userInput = array();
        $input = $request->all();
        $cosmeticId = 	$input['cosmetic_id'];

        $validator = Validator::make($request->all(), [
            'cosmetic_id' =>['required','numeric'],
        ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }else{

                /*delete treatment center */
                $cosmeticdata = CosmeticDeliveries::find($cosmeticId);
                if(isset($cosmeticdata) && !empty($cosmeticdata)){
                    $cosmeticdata->status=2;
                    $cosmeticdata->save();

                     /* Get treatment centre name by Id */
                    $getTreatmentCentreData = TreatmentCenter::getTreatmentCenterDetail($input['treatment_center_id']);
                    /* Get treatment centre name by Id */
                    $getCompanyName = '';
                    if(!empty($getTreatmentCentreData[0])){
                        $getCompanyName = ucfirst($getTreatmentCentreData[0]->full_company_name);
                    }

                    if($cosmeticdata){
                        $moduleName = 'Cosmetic Deliveries';
                        $moduleActivity = 'Deleted Cosmetic Deliveries';
                        // $description =  ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has deleted Cosmetic Deliveries for '.$getCompanyName.' Treatment centre.';
                        $description =  "Cosmetic delivery deleted for ".$getCompanyName." Treatment Centre";

                        $requestData = array('cosmetic_id'=>$cosmeticId);
                        $usercompanyName = getUserCompanyName(Auth::user());
                        /*Add action in audit log*/
                        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                        /*Add action in audit log*/

                        $message = 'Cosmetic Deliveries deleted successfully';
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
                    $message = 'Cosmetic Deliveries not found';
                    $response = [
                        'success' => 'fail',
                        'message' => $message,
                    ];
                    return response()->json($response, 404);
                }
                /*delete treatment center */
            }
        }

        function getDiagnosticData(){
            return view('diagnostic_data');
        }
    }
