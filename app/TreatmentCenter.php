<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;
use DB;

class TreatmentCenter extends Model
{
     protected $table = 'treatment_center';

    public function country(){
        return $this->belongsTo('App\Country');
    }

     /**
     * get active treatment center list
     * @return obj treatment center data with distributor
     */
    static function getTreatmentCenterList($centerId=null,$searchData=null){
        $authUser = Auth::user();

         $getTreatmentCenterData =  DB::table('treatment_center as t');
                                    if(!empty($searchData)){
                                        $getTreatmentCenterData->select('t.treatment_ema_code','t.full_company_name',DB::raw("(SELECT pr.name  FROM country c INNER JOIN secondary_region sr ON sr.id = c.fk_secondary_region_id INNER JOIN primary_region pr ON pr.id = sr.fk_primary_region_id WHERE c.id = t.country_id) AS primary_region"),'t.name_of_primary_contact','t.email_of_primary_contact','t.mobile_number_of_primary_contact',DB::raw("(SELECT Count(h.id) FROM  hydracool_srp h WHERE  h.fk_treatment_centers_id = t.id AND t.status IN( 1, 3 ) AND h.status IN( 1, 3 ) ) AS install_device"),'t.status');
                                    }else{
                                        $getTreatmentCenterData->select('t.*',DB::raw("(SELECT pr.name  FROM country c INNER JOIN secondary_region sr ON sr.id = c.fk_secondary_region_id INNER JOIN primary_region pr ON pr.id = sr.fk_primary_region_id WHERE c.id = t.country_id) AS primary_region,(SELECT Count(h.id) FROM  hydracool_srp h WHERE  h.fk_treatment_centers_id = t.id AND t.status IN( 1, 3 ) AND h.status IN( 1, 3 ) ) AS install_device"));
                                    }
                                    if($centerId){
                                        $getTreatmentCenterData->where('t.id',$centerId);
                                    }

                                    if($authUser->hasRole(['distributor principal']) || $authUser->hasRole(['distributor service']) || $authUser->hasRole(['distributor sales'])){
                                        $getDistributorCompanyData = getDistributorCompanyDataById();
                                        $getTreatmentCenterData->where('t.distributors',$getDistributorCompanyData->distributor_company_id);
                                    }

                                    if(!empty($searchData)){
                                        foreach($searchData as $key=>$val){
                                            if($val){
                                                if($key=="install_device"){
                                                    $getTreatmentCenterData->having($key,$val);
                                                }else if($key=="primary_region"){
                                                    $getTreatmentCenterData->having($key,'LIKE','%'.$val.'%');
                                                }else{
                                                    $getTreatmentCenterData->where('t.'.$key,'LIKE',"%".$val."%");
                                                }
                                            }
                                        }
                                    }
                                    $getTreatmentCenterData->whereIn('t.status',[1,3]);
                                    $getTreatmentCenterData->orderBy('t.id','desc');

                                    if(!empty($searchData)){
                                        $getTreatmentCenterData = $getTreatmentCenterData->get();
                                        $getTreatmentCenterData = $getTreatmentCenterData->map(function ($item, $key) {
                                            return (array) $item;
                                        })
                                        ->all();
                                        if(!empty($getTreatmentCenterData)){
                                            foreach($getTreatmentCenterData as $key=>$getTreatmentCenterRow){
                                                if($getTreatmentCenterRow['status']==3 ){
                                                    $getTreatmentCenterRow['status'] = 'Suspended';
                                                }else{
                                                    $getTreatmentCenterRow['status'] = 'Active';
                                                }
                                                $getTreatmentCenterData[$key]  = $getTreatmentCenterRow;
                                            }
                                        }
                                    }

        return $getTreatmentCenterData;
    }


     /**
     * get active treatment center list
     * @return obj treatment center data with distributor
     */
    static function getTreatmentCenterDetail($centerId){
        $getTreatmentCenterData =  TreatmentCenter::join('country as c','treatment_center.country_id','=','c.id')
                                                ->leftJoin('distributors as d','treatment_center.distributors','=','d.id')
                                                ->select('c.name as country_name','treatment_center.*','d.full_company_name as distributo_name')
                                                ->where('treatment_center.id',$centerId)
                                                ->get();
        return $getTreatmentCenterData;
    }



    /**
     * Get Active treatment center Principal list data
     * @return obj
     */
    static function getTreatmentCenterPrincipalList($centerId,$searchData=null){
        $authUser = Auth::user();
        $getTreatmentCenterPrincipalData = TreatmentCenter::join('treatment_center_user_mapping as tcm','treatment_center.id','=','tcm.fk_treatment_center_id')
                                                          ->join('users as u','tcm.fk_user_id','=','u.id')
                                                          ->join('roles','roles.id','=','tcm.fk_role_id');

                                            if(!empty($searchData)){
                                                $getTreatmentCenterPrincipalData->select('u.name','roles.name as roles_name','u.is_logged_in','u.status','u.email','u.primary_telephone_number','u.mobile_telephone_number');
                                            }else{
                                                $getTreatmentCenterPrincipalData->select('u.id','u.name','u.username','roles.name as roles_name','u.email','u.primary_telephone_number','u.mobile_telephone_number','u.is_logged_in','u.status','u.created_at','treatment_center.status as treatmentcentre_status');
                                            }
                                            $getTreatmentCenterPrincipalData->where('treatment_center.id',$centerId);

                                            if(!empty($searchData)){
                                                foreach($searchData as $key=>$val){
                                                    if($val){
                                                        $getTreatmentCenterPrincipalData->where('u.'.$key,'LIKE',"%".$val."%");
                                                    }

                                                }
                                            }

                                            if(!$authUser->hasRole(['system administrator'])){
                                                $getTreatmentCenterPrincipalData->where('u.id','!=',$authUser->id);
                                            }

                                            $getTreatmentCenterPrincipalData->where('tcm.fk_role_id',5);
                                            $getTreatmentCenterPrincipalData->where('u.deleted_at',NULL);

                                            $getTreatmentCenterPrincipalData->whereIn('u.status',[1,3]);

                                            $getTreatmentCenterPrincipalData->orderby('u.id','desc');

                                            if(!empty($searchData)){
                                                $getTreatmentCenterPrincipalData = $getTreatmentCenterPrincipalData->get()->toArray();
                                            }
        return $getTreatmentCenterPrincipalData;
   }


   /**
   * Get Active treatment center Principal list count and hydracool spr
   * @return obj
   */
   static function getTreatmentCenterAssociatedActiveCount($centerId){
    $getActiveHydraCoolSrp =  HydraCoolSrp::where('fk_treatment_centers_id',$centerId)
                                          ->whereIn('status',[1,3])
                                          ->count();

    $getActivePrincipal =  TreatmentCenterUser::join('users as u','treatment_center_user_mapping.fk_user_id','=','u.id')
                                              ->where('treatment_center_user_mapping.fk_treatment_center_id',$centerId)
                                              ->whereIn('u.status',[1,3])
                                              ->count();

    $getActiveDistributorPrincipal =  TreatmentCenter::join('distributors as d','treatment_center.distributors','=','d.id')
                                            ->join('distributor_user_mapping as ds','ds.fk_distributor_id','=','d.id')
                                            ->where('treatment_center.id',$centerId)
                                            ->whereIn('treatment_center.status',[1,3])
                                            ->count();

    return [$getActiveHydraCoolSrp,$getActivePrincipal,$getActiveDistributorPrincipal];
   }

   // get Treatment center data
   static function getTreatmentList($id,$searchData=null){
    $authUser = Auth::user();

    $getTreatmentCenterData =  DB::table('treatment_center as t');
                               if(!empty($searchData)){
                                   $getTreatmentCenterData->select('t.treatment_ema_code','t.full_company_name',DB::raw("(SELECT pr.name  FROM country c INNER JOIN secondary_region sr ON sr.id = c.fk_secondary_region_id INNER JOIN primary_region pr ON pr.id = sr.fk_primary_region_id WHERE c.id = t.country_id) AS primary_region"),'t.name_of_primary_contact','t.email_of_primary_contact','t.mobile_number_of_primary_contact',DB::raw("(SELECT Count(h.id) FROM  hydracool_srp h WHERE  h.fk_treatment_centers_id = t.id AND t.status IN( 1, 3 ) AND h.status IN( 1, 3 ) ) AS install_device"),'t.status');
                               }else{
                                   $getTreatmentCenterData->select('t.*',DB::raw("(SELECT pr.name  FROM country c INNER JOIN secondary_region sr ON sr.id = c.fk_secondary_region_id INNER JOIN primary_region pr ON pr.id = sr.fk_primary_region_id WHERE c.id = t.country_id) AS primary_region,(SELECT Count(h.id) FROM  hydracool_srp h WHERE  h.fk_treatment_centers_id = t.id AND t.status IN( 1, 3 ) AND h.status IN( 1, 3 ) ) AS install_device"));
                               }
                               $getTreatmentCenterData->where('t.distributors',$id);

                               if($authUser->hasRole(['distributor principal']) || $authUser->hasRole(['distributor service']) || $authUser->hasRole(['distributor sales'])){
                                   $getDistributorCompanyData = getDistributorCompanyDataById();
                                   $getTreatmentCenterData->where('t.distributors',$getDistributorCompanyData->distributor_company_id);
                               }

                               if(!empty($searchData)){
                                   foreach($searchData as $key=>$val){
                                       if($val){
                                           if($key=="install_device"){
                                               $getTreatmentCenterData->having($key,$val);
                                           }else if($key=="primary_region"){
                                               $getTreatmentCenterData->having($key,'LIKE','%'.$val.'%');
                                           }else{
                                               $getTreatmentCenterData->where('t.'.$key,'LIKE',"%".$val."%");
                                           }
                                       }
                                   }
                               }
                               $getTreatmentCenterData->whereIn('t.status',[1,3]);

                               $getTreatmentCenterData->orderBy('t.id','desc');


                               if(!empty($searchData)){
                                    $getTreatmentCenterData = $getTreatmentCenterData->get();
                                    $getTreatmentCenterData = $getTreatmentCenterData->map(function ($item, $key) {
                                        return (array) $item;
                                    })->all();
                                    if(!empty($getTreatmentCenterData)){
                                        foreach($getTreatmentCenterData as $key=>$getTreatmentCenterRow){
                                            if($getTreatmentCenterRow['status']==3 ){
                                                $getTreatmentCenterRow['status'] = 'Suspended';
                                            }else{
                                                $getTreatmentCenterRow['status'] = 'Active';
                                            }
                                            $getTreatmentCenterData[$key]  = $getTreatmentCenterRow;
                                        }
                                    }
                                }

   return $getTreatmentCenterData;
    }

    // suspend/release treatment centre principla
    static function updateTreatmentCentrePrincipalStatus($centerId,$status){
        $getActivePrincipal =  TreatmentCenter::join('treatment_center_user_mapping as mapping','mapping.fk_treatment_center_id','=','treatment_center.id')
                                              ->join('users as u','u.id','=','mapping.fk_user_id')
                                              ->where('mapping.fk_treatment_center_id',$centerId)
                                              ->where('u.deleted_at',NULL)
                                              ->update(['u.status' => $status,'u.is_logged_in'=>'0']);
    }

    static function getTreatmentCentreDetails($serialNumber)
    {
        $getTreatmentCentreDetails =  TreatmentCenter::join('hydracool_srp as hs','hs.fk_treatment_centers_id','=','treatment_center.id')
                                                    ->select('treatment_center.*','hs.id as hydracoolspr_id')
                                                    ->where('hs.serial_number',$serialNumber)
                                                    ->where('hs.status',1)
                                                    ->whereIn('treatment_center.status',[1,3])
                                                    ->get()
                                                    ->toArray();
        return $getTreatmentCentreDetails;
    }
}
