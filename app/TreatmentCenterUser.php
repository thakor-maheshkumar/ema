<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TreatmentCenterUser extends Model
{
    protected $table = 'treatment_center_user_mapping';

    /**
    * getUsersList get the treatment center users list principal/sales
    * @return obj $getUsersList
    */
    static function getUsersList($roleId,$principalId = null){

        $getUsersList = User::select('users.*')
                            ->join('treatment_center_user_mapping','treatment_center_user_mapping.fk_user_id','=','users.id')
                            ->whereIn('users.status',[1,3])
                            ->where('treatment_center_user_mapping.fk_role_id',$roleId);
                            if(!empty($principalId)){
                               $getUsersList->where('users.id',$principalId);
                            }

        $getUsersList = $getUsersList->get();
        return $getUsersList;

    }
}
