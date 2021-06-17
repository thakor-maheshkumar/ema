<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TreatmentCentreCSN extends Model
{
    protected $table = 'treatment_json_csn'; //table name


    static function getJSONCSNData($fk_treatment_json_id){
        $getTreatmentJsonCSNData =TreatmentCentreCSN::where('fk_treatment_json_id',$fk_treatment_json_id)
                                                    ->get()
                                                    ->toArray();
        return $getTreatmentJsonCSNData;
    }
}
