<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TreatmentJson;
use App\TreatmentSystemJson;
use App\TreatmentBootJson;
use App\HydraCoolSrp;
use App\HydraCoolSrpUnits;
use App\TreatmentCentrePAR;
use App\TreatmentCentreWAE;
use App\TreatmentCentreCSN;
use App\TreatmentCentreFile;
use App\TreatmentJsonParent;
use App\User;
use Mail;
use App\Mail\SendDynamicEmail;

class LambdaController extends Controller
{

    public function addJsonData(Request $request)
    {


        $getFileType = $request->input('type');
        $getTableName = $request->input('table_name');
        $getData = $request->input('json_data');

      /*Get Hydracool SRP ID*/
            $getHydraCoolSRPData = HydraCoolSrp::select('id','fk_treatment_centers_id')
                                                ->where('serial_number',$getData['DSN'])
                                                ->where('status',1)
                                                ->first();
            $getHydraCoolSRPID = $getHydraCoolSRPData->id;
            $getTreatmentCentreId = $getHydraCoolSRPData->fk_treatment_centers_id;
    /*Get Hydracool SRP ID*/

        if($getTableName=="treatment_boot_json"){

            $HubBoard = 0;
            $Vibrox = 0;
            $ultraB = 0;
            $Collagen = 0;
            $MicroT = 0;

            /*Get Hydracool SRP ID*/
                $getHydraCoolSRPData = HydraCoolSrp::select('id')
                                                ->where('serial_number',$getData['DSN'])
                                                ->where('status',1)
                                                ->first();
                $getHydraCoolSRPID = $getHydraCoolSRPData->id;
            /*Get Hydracool SRP ID*/

            /*Get HydraCool SRP Units*/
                $getHydraCoolSRPUnitData = HydraCoolSrpUnits::select('id','title')
                                                    ->where('fk_hydracool_srp_id',$getHydraCoolSRPID)
                                                    ->where('status',1)
                                                    ->first();
                $getHydraCoolSRPUnit = $getHydraCoolSRPUnitData->title;
                $getHydraCoolSRPUnit = json_decode($getHydraCoolSRPUnit);
            /*Get HydraCool SRP Units*/

            /*Compare json unit and db unit value*/
                $arrHubboard = $getData['FLB'][0];
                $arrUltraB =   $getData['ULB'][0];
                $arrVibrox =   $getData['VIB'][0];
                $arrMicroT =   $getData['MIB'][0];
                $arrCollagen = $getData['COB'][0];

                $newUnitArray = array();

                if($getHydraCoolSRPUnit->MicroT_unit != $arrMicroT){
                    $MicroT = 1;
                    $newUnitArray['MicroT_unit'] = $arrMicroT;
                }else{
                    $newUnitArray['MicroT_unit'] = $getHydraCoolSRPUnit->MicroT_unit;
                }

                if($getHydraCoolSRPUnit->UltraB_unit != $arrUltraB){
                    $ultraB = 1;
                    $newUnitArray['UltraB_unit'] = $arrUltraB;
                }else{
                    $newUnitArray['UltraB_unit'] = $getHydraCoolSRPUnit->UltraB_unit;
                }

                if($getHydraCoolSRPUnit->VibroX_unit != $arrVibrox){
                    $Vibrox = 1;
                    $newUnitArray['VibroX_unit'] = $arrVibrox;
                }else{
                    $newUnitArray['VibroX_unit'] = $getHydraCoolSRPUnit->VibroX_unit;
                }

                if($getHydraCoolSRPUnit->Collagen_unit != $arrCollagen){
                    $Collagen = 1;
                    $newUnitArray['Collagen_unit'] = $arrCollagen;
                }else{
                    $newUnitArray['Collagen_unit'] = $getHydraCoolSRPUnit->Collagen_unit;
                }

                if($getHydraCoolSRPUnit->Hub_Board_unit != $arrHubboard){
                    $HubBoard = 1;
                    $newUnitArray['Hub_Board_unit'] = $arrHubboard;
                }else{
                    $newUnitArray['Hub_Board_unit'] = $getHydraCoolSRPUnit->Hub_Board_unit;
                }
            /*Compare json unit and db unit value*/
             if($MicroT == 1 || $ultraB==1 || $Vibrox==1 || $Collagen==1 || $HubBoard==1){

                $affectedRows = HydraCoolSrpUnits::where('fk_hydracool_srp_id',$getHydraCoolSRPID)->update(array('status' => 0));

                $HydraCoolSrpUnits = new HydraCoolSrpUnits;
                $HydraCoolSrpUnits->fk_hydracool_srp_id = $getHydraCoolSRPID;
                $HydraCoolSrpUnits->title = json_encode($newUnitArray);
                $HydraCoolSrpUnits->save();
             }


        	$treatmentBootJson = new TreatmentBootJson;

            $treatmentBootJson->FLB = isset($getData['FLB']) ? json_encode($getData['FLB']) : "";
            $treatmentBootJson->ULB = isset($getData['ULB']) ? json_encode($getData['ULB']): "";
            $treatmentBootJson->VIB = isset($getData['VIB']) ? json_encode($getData['VIB']) : "";
            $treatmentBootJson->MIB = isset($getData['MIB']) ? json_encode($getData['MIB']) : "";
            $treatmentBootJson->COB = isset($getData['COB']) ? json_encode($getData['COB']) : "";
        	$treatmentBootJson->DSN = isset($getData['DSN']) ? $getData['DSN'] : "";
        	$treatmentBootJson->TYP = isset($getData['TYP']) ? $getData['TYP'] : "";
        	$treatmentBootJson->UTI = isset($getData['UTI']) ? $getData['UTI'] : "";
        	$treatmentBootJson->USI = isset($getData['USI']) ? $getData['USI'] : "";
        	$treatmentBootJson->DFW = isset($getData['DFW']) ? $getData['DFW'] : "";
        	$treatmentBootJson->STA = isset($getData['STA']) ? $getData['STA'] : "";
        	$treatmentBootJson->UST = isset($getData['UST']) ? $getData['UST'] : "";
        	$treatmentBootJson->RSS = isset($getData['RSS']) ? $getData['RSS'] : "";
            $treatmentBootJson->GSB = isset($getData['GSB']) ? json_encode($getData['GSB']) : "";
        	$treatmentBootJson->MAB = isset($getData['MAB']) ? json_encode($getData['MAB']) : "";
        	$treatmentBootJson->ULB = isset($getData['COP']) ? json_encode($getData['COP']) : "";
        	$treatmentBootJson->CSN = isset($getData['CSN']) ? json_encode($getData['CSN']) : "";
        	$treatmentBootJson->WAE = isset($getData['WAE']) ? json_encode($getData['WAE']) : "";
        	$treatmentBootJson->save();
             $referenceId = $treatmentBootJson->id;

        }else if($getTableName=="treatment_json"){

            /* Explode the UID and Flag */
                $UID = $getData['UID'][0];
                $UID_FLAG = $getData['UID'][1];
            /* Explode the UID and Flag */

            /* check technology */
                $getTechnology = $getData['TEC'];
                $technology  = $getTechnology[1];
            /* check technology */


            /* Check UID and flag is available in parent table */
            if($UID_FLAG==1){
                    $checkPreviousDataAvailable = TreatmentJson::select('UID_flag','unique_UID_value')->where('UID',$UID)->where('DSN',$getData['DSN'])->orderBy('id','desc')->limit('1')->get()->first();

                    if(!empty($checkPreviousDataAvailable) && $checkPreviousDataAvailable->UID_FLAG==0){
                        $treatmentJsonParent  = new TreatmentJsonParent;
                        $treatmentJsonParent->UID = $getData['UID'][0];
                        $treatmentJsonParent->UID_FLAG = 1;
                        $treatmentJsonParent->DSN = $getData['DSN'];
                        $treatmentJsonParent->save();
                        $getId  = $treatmentJsonParent->id;
                    }else{
                        $getFirstData = TreatmentJsonParent::where('UID',$UID)->where('UID_flag',1)->where('DSN',$getData['DSN'])->orderBy('id','desc')->get()->first();

                        /* check if data is available  */
                        if(empty($getFirstData)){
                            $treatmentJsonParent  = new TreatmentJsonParent;
                            $treatmentJsonParent->UID = $getData['UID'][0];
                            $treatmentJsonParent->UID_FLAG = 1;
                            $treatmentJsonParent->DSN = $getData['DSN'];
                            $treatmentJsonParent->save();
                            $getId  = $treatmentJsonParent->id;
                        }else{
                            $getId = $getFirstData->id;
                        }

                    }
            }else{
                $getFirstData = TreatmentJsonParent::where('UID',$UID)->where('UID_flag',1)->where('DSN',$getData['DSN'])->orderBy('id','desc')->get()->first();

                /* check if data is available  */
                if(empty($getFirstData)){
                    $treatmentJsonParent  = new TreatmentJsonParent;
                    $treatmentJsonParent->UID = $getData['UID'][0];
                    $treatmentJsonParent->UID_FLAG = 1;
                    $treatmentJsonParent->DSN = $getData['DSN'];
                    $treatmentJsonParent->save();
                    $getId  = $treatmentJsonParent->id;
                }else{
                    $getId = $getFirstData->id;
                }
            }
            /* Check UID and flag is available in parent table */


        	$treatmentJson = new TreatmentJson;
        	$treatmentJson->MOD = isset($getData['MOD']) ? $getData['MOD'] : "";
        	$treatmentJson->USF = isset($getData['USF']) ? date('Y-m-d H:i:S',strtotime($getData['USF'])) : "";
        	$treatmentJson->UTF = isset($getData['UTF']) ? date('Y-m-d H:i:S',strtotime($getData['UTF'])) : "";
        	$treatmentJson->UTI = isset($getData['UTI']) ? date('Y-m-d H:i:S',strtotime($getData['UTI'])) : "";
        	$treatmentJson->TYP = isset($getData['TYP']) ? $getData['TYP'] : "";
        	$treatmentJson->USI = isset($getData['USI']) ? date('Y-m-d H:i:S',strtotime($getData['USI'])) : "";
            $treatmentJson->UID = $UID;
            $treatmentJson->UID_flag = $UID_FLAG;
            $treatmentJson->unique_UID_value = $getId;
            $treatmentJson->SKC = isset($getData['SKC']) ? json_encode($getData['SKC']) : "";
        	$treatmentJson->TEC = isset($getData['TEC']) ? json_encode($getData['TEC']) : "";
        	$treatmentJson->SKT = isset($getData['SKT']) ? $getData['SKT'] : "";
        	$treatmentJson->STA = isset($getData['STA']) ? $getData['STA'] : "";
            $treatmentJson->DSN = isset($getData['DSN']) ? $getData['DSN'] : "";
            $treatmentJson->imsi = isset($getData['imsi']) && !empty($getData['imsi'])? explode(':',$getData['imsi'])[1] : "";
        	$treatmentJson->save();

        	if($treatmentJson->save())
            {

                $referenceId = $treatmentJson->id;
                $treatmentJsonCSN = isset($getData['CSN']) && !empty($getData['CSN']) ? $getData['CSN'] : "";
                if($treatmentJsonCSN){

	                foreach ($treatmentJsonCSN as $key => $value) {
	                    $data = [
	                     'fk_treatment_json_id'  => $treatmentJson->id,
	                     'bottle_id' =>  isset($value[0]) && !empty($value[0]) ? $value[0] : '',
	                     'initial_value' => isset($value[1]) && !empty($value[1]) ? $value[1] : '',
	                     'end_value'  => isset($value[2]) && !empty($value[2]) ? $value[2] : '',
                        ];

	                    TreatmentCentreCSN::insert($data);
	                }
                }


                $treatmentJsonWAE = isset($getData['WAE']) && !empty($getData['WAE'])  ? $getData['WAE'] : "";
                 if($treatmentJsonWAE){
	                foreach ($treatmentJsonWAE as $key => $value1) {

	                    $data1 = [
	                     'fk_treatment_json_id'  => $treatmentJson->id,
	                     'warning_datetime' =>  isset($value1[0]) && !empty($value1[0]) ? $value1[0] : '',
	                     'warning_code' =>  isset($value1[1]) && !empty($value1[1]) ? $value1[1] : '',
	                     'warning_value'  => isset($value1[2]) && !empty($value1[2]) ? $value1[2] : '',
	                    ];
	                    TreatmentCentreWAE::insert($data1);
	                }
	            }

                $treatmentJsonPAR = isset($getData['PAR']) && !empty($getData['PAR']) ? $getData['PAR'] : "";
                $data2 = [];
                if($treatmentJsonPAR){
                foreach ($treatmentJsonPAR as $key => $value2) {


                    if($technology=="AQ"){
                        $data2[] = [
                            'fk_treatment_json_id'  => $treatmentJson->id,
                            'treatment_time' => isset($value2[0]) && !empty($value2[0]) ? $value2[0] : "",
                            'time_elapsed' => isset($value2[1]) && !empty($value2[1]) ? $value2[1] : "",
                            'intensity_of_vacuum'  => isset($value2[2]) && !empty($value2[2]) ? $value2[2] : '',
                            'flow'  => isset($value2[3]) && !empty($value2[3]) ? $value2[3] : '',
                            'bottle'  => isset($value2[4]) && !empty($value2[4]) ? $value2[4] : '',
                            'enabled' => isset($value2[5]) && !empty($value2[5]) ? $value2[5] : "",
                           ];

                    }else if($technology=="CO"){
                        $data2[] = [
                            'fk_treatment_json_id'  =>  $treatmentJson->id,
                            'treatment_time' => isset($value2[0]) && !empty($value2[0]) ? $value2[0] : "",
                            'time_elapsed' => isset($value2[1]) && !empty($value2[1]) ? $value2[1] : "",
                            'intensity_of_vacuum'  => isset($value2[2]) && !empty($value2[2]) ? $value2[2] : '',
                            'vacuum'  => isset($value2[3]) && !empty($value2[3]) ? $value2[3] : '',
                            'pulsed'  => isset($value2[4]) && !empty($value2[4]) ? $value2[4] : '',
                            'enabled' => isset($value2[5]) && !empty($value2[5]) ? $value2[5] : "",
                           ];

                    }else if($technology=="VI"){
                        $data2[] = [
                            'fk_treatment_json_id'  =>  $treatmentJson->id,
                            'treatment_time' => isset($value2[0]) && !empty($value2[0]) ? $value2[0] : "",
                            'time_elapsed' => isset($value2[1]) && !empty($value2[1]) ? $value2[1] : "",
                            'intensity_of_vacuum'  => isset($value2[2]) && !empty($value2[2]) ? $value2[2] : '',
                            'mode_selected'  => isset($value2[3]) && !empty($value2[3]) ? $value2[3] : '',
                            'enabled' => isset($value2[4]) && !empty($value2[4]) ? $value2[4] : "",
                           ];
                    }else if($technology=="MI" || $technology=="UL"){
                        $data2[] = [
                            'fk_treatment_json_id'  =>  $treatmentJson->id,
                            'treatment_time' => isset($value2[0]) && !empty($value2[0]) ? $value2[0] : "",
                            'time_elapsed' => isset($value2[1]) && !empty($value2[1]) ? $value2[1] : "",
                            'intensity_of_vacuum'  => isset($value2[2]) && !empty($value2[2]) ? $value2[2] : '',
                            'enabled' => isset($value2[3]) && !empty($value2[3]) ? $value2[3] : "",
                           ];
                    }
                }
                TreatmentCentrePAR::insert($data2);
	            }
            }
        }else if($getTableName=="treatment_system_json"){
        	$treatmentSystemJson = new TreatmentSystemJson;
        	$treatmentSystemJson->DSN = isset($getData['DSN']) ? $getData['DSN'] : "";
        	$treatmentSystemJson->TYP = isset($getData['TYP']) ? $getData['TYP'] : "";
        	$treatmentSystemJson->UTI = isset($getData['UTI']) ? $getData['UTI'] : "";
        	$treatmentSystemJson->USI = isset($getData['USI']) ? $getData['USI'] : "";
        	$treatmentSystemJson->WAE = isset($getData['WAE']) ? json_encode($getData['WAE']) : "";
        	$treatmentSystemJson->save();
            $referenceId = $treatmentSystemJson->id;
        }


            /* save request data in db*/
                $treatmentCentreFile = new TreatmentCentreFile;
                $treatmentCentreFile->treatmentcentre_id =$getTreatmentCentreId;
                $treatmentCentreFile->fk_reference_id =$referenceId;
                $treatmentCentreFile->data = json_encode($getData);
                $treatmentCentreFile->uploaded_source = 'Lambda';
                $treatmentCentreFile->ip_address =request()->ip();
                $treatmentCentreFile->save();
            /* save request data in db*/
        return response()->json($request->all(), 200);
    }
    public function sendemail(Request $request)
    {
        try{
            $users = User::whereHas("roles", function($q){
                $q->whereIn("name", ['system administrator']);
            })->get();

            foreach ($users as $key => $user) {
                if(count($request->all()) > 0) {
                    Mail::to($user->email)->queue(new SendDynamicEmail($request->all()));
                }
            }
            $response = array("status" => 1, "msg" => "Mail sent");
            return response()->json($response, 200);

        }catch(\Exception $e){
            
            $response = array("status" => 0, "msg" => $e->getMessage());
            return response()->json($response, 500);
        }
                 
    }
}
