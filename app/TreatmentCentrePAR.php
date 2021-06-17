<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TreatmentCentrePAR extends Model
{
    protected $table = 'treatment_json_par'; //table name

    static function getBottleValue($fk_treatment_json_id){
        $bottleTickeIndicator = array();
        $getTreatmentBottleData = TreatmentCentrePAR::select('id','bottle')->whereIn('fk_treatment_json_id',$fk_treatment_json_id)->groupBy('bottle')->get()->toArray();

        if(!empty($getTreatmentBottleData))
        {
            foreach($getTreatmentBottleData as $getTreatmentBottle)
            {
                $bottleTickeIndicator[] =$getTreatmentBottle['bottle'];
            }
        }
        return $bottleTickeIndicator;
    }


    static function getModSelectedValue($fk_treatment_json_id){
        $modSelectedTickeIndicator = array();
        $getTreatmentModSelectedData = TreatmentCentrePAR::select('id','mode_selected')->whereIn('fk_treatment_json_id',$fk_treatment_json_id)->groupBy('mode_selected')->get()->toArray();

        if(!empty($getTreatmentModSelectedData))
        {
            foreach($getTreatmentModSelectedData as $getTreatmentModSelected)
            {
                $modSelectedTickeIndicator[] =$getTreatmentModSelected['mode_selected'];
            }
        }
        return $modSelectedTickeIndicator;
    }

    static function getJSONPARData($tretmentcentreJsonId){
        $getTreatmentJsonPARData =TreatmentCentrePAR::where('fk_treatment_json_id',$tretmentcentreJsonId)
                                                    ->get()
                                                    ->toArray();
        return $getTreatmentJsonPARData;
    }
}
