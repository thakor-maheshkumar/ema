<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\TreatmentCentrePAR;
use App\TreatmentCentreWAE;
use App\TreatmentCentreCSN;

class TreatmentJson extends Model
{
    protected $table = 'treatment_json';

    static function getTreatmentJson($parentId=null)
    {
        $getTreatmentJsonData =  DB::table('treatment_json')
                                    ->select("*")
                                    ->where('unique_UID_value',$parentId)
                                    ->get()->toArray();
        return $getTreatmentJsonData;
    }


    static function editTreatmentJson($tretmentcentreJsonId)
    {

            $getTreatmentJsonData =  TreatmentJson::where('id',$tretmentcentreJsonId)
                                                  ->first();

            $getTreatmentJsonPARData =  TreatmentCentrePAR::getJSONPARData($tretmentcentreJsonId);
            $getTreatmentJsonCSNData =  TreatmentCentreCSN::getJSONCSNData($tretmentcentreJsonId);
            $getTreatmentJsonWAEData =  TreatmentCentreWAE::getJSONWAEData($tretmentcentreJsonId);

            $finalArray = array($getTreatmentJsonData,$getTreatmentJsonPARData,$getTreatmentJsonCSNData,$getTreatmentJsonWAEData);
            return $finalArray;
    }


}
