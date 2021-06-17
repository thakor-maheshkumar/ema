<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;

class DistributorUser extends Model
{
    protected $table='distributor_user_mapping';

    static function getUsersList($roleId,$id,$searchData=null){
        $authUser = Auth::user();
        $getUsersList = User::join('distributor_user_mapping','distributor_user_mapping.fk_user_id','=','users.id')
        ->join('roles','roles.id','=','distributor_user_mapping.fk_role_id')
        ->where('distributor_user_mapping.fk_distributor_id',$id)
        ->whereIn('users.status',[1,3])
        ->orderBy('users.id','DESC');
        if(!empty($searchData)){
            $getUsersList->select('users.name','roles.name as roles_name','users.is_logged_in as online','users.status as new_status','users.email','users.primary_telephone_number','users.mobile_telephone_number');
        }
        else{
            $getUsersList->select('users.*','roles.name as roles_name','users.is_logged_in as online','users.status as new_status','distributor_user_mapping.fk_distributor_id');
        }

        if(!$authUser->hasRole(['system administrator'])){
            $getUsersList->where('users.id','!=',$authUser->id);
        }


        if(!empty($searchData)){
            foreach($searchData as $key=>$val){
                if($val){
                    if($key=='name'){
                        $getUsersList->where('users.'.$key,'LIKE',"%".$val."%");
                    }else if($key=="role"){
                        $getUsersList->where('roles.name','LIKE',"%".$val."%");
                    }else{
                        $getUsersList->where($key,'LIKE',"%".$val."%");
                    }
                }
            }
        }
        if(!empty($searchData)){
            $getUsersList = $getUsersList->get()->each(function($row){
                $row->setHidden(['role_acl_name']);
            })->toArray();
        }

        return $getUsersList;
    }
}
