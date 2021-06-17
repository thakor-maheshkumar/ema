<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\TreatmentJson;

class TreatmentJsonParent extends Model
{
    protected $table = 'treatment_json_parent';

    static function getTreatmentParentJson($getTreatmentCenterId=null,$serialNumber = null)
    {
            if($getTreatmentCenterId){
            $getTreatmentJsonData = array();
            $getHydraCoolSRPData =  DB::table('hydracool_srp')
                                       ->select('serial_number')
                                       ->where('fk_treatment_centers_id',$getTreatmentCenterId)
                                       ->where('status','1')
                                      ->get()->toArray();
             if(!empty($getHydraCoolSRPData)){
                $activeSerialNumber = array();
                foreach($getHydraCoolSRPData as $getHydraCoolSRPval){
                    $activeSerialNumber[] = $getHydraCoolSRPval->serial_number;

                }
            }

            if(!empty($activeSerialNumber)){
            $getTreatmentJsonData =  DB::table('treatment_json_parent')
                                        ->select("*")
                                        ->whereIn('DSN',$activeSerialNumber)
                                        ->orderBy('id','asc')
                                        ->get()->toArray();

            }
        }else{
            $getTreatmentJsonData = DB::table('treatment_json_parent')
                                        ->select("*")
                                        ->orderBy('id','asc')
                                        ->get()->toArray();
        }
            return $getTreatmentJsonData;
    }
}
