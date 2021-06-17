<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TreatmentCentreWAE extends Model
{
    protected $table = 'treatment_json_wae'; //table name


    static function getJSONWAEData($tretmentcentreJsonId){
        $getTreatmentJsonWAEData =TreatmentCentreWAE::where('fk_treatment_json_id',$tretmentcentreJsonId)
                                                    ->get()
                                                    ->toArray();
        return $getTreatmentJsonWAEData;
    }

}
