<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class HydraCoolSrp extends Model
{
    protected $table = 'hydracool_srp';

   //Primary Key
    public $primaryKey = 'id';

    public function hydracoolsrpunits() {
       return $this->hasMany('App\HydraCoolSrpUnits','fk_hydracool_srp_id');
   }

    /**
    * Get hydracool srp serial number list
    * @return array
    */
    static function getHydraCoolSrpSerialNumber($serialNumber=null){
        $getHydracoolSrpData =  TreatmentCenter::join('hydracool_srp','treatment_center.id','=','hydracool_srp.fk_treatment_centers_id')
        ->join('hydra_cool_srp_units as units','hydracool_srp.id','=','units.fk_hydracool_srp_id')
        ->select('units.title','units.id as handset_id','hydracool_srp.id as hydracoolsrp_id','hydracool_srp.serial_number','hydracool_srp.is_demo','hydracool_srp.fk_treatment_centers_id as treatment_center_id','treatment_center.full_company_name as treatment_center_name')
        ->where('treatment_center.is_ema','1')
        ->where('treatment_center.status','1')
        ->where('hydracool_srp.status','1');
        if(!empty($serialNumber)){
            $getHydracoolSrpData->where('hydracool_srp.serial_number',$serialNumber);
        }
        $getHydracoolSrpData = $getHydracoolSrpData->get();
        return $getHydracoolSrpData;
    }

    /**
    * Check serial number unique
    * @return count
    */
    static function checkSerialNumberUnique($serialNumber,$exceptId=null){
        $getCount = HydraCoolSrp::where('serial_number',$serialNumber)
        ->where('status',1);
        if(isset($exceptId) && !empty($exceptId)){
            $getCount->where('id','!=',$exceptId);
        }
        $getCount = $getCount->count();
        return $getCount;
    }

    /**
    * Check srp hydracool exist with serial number
    * @return array
    */
    static function checkSerialNumberExist($serialNumber){

        $gethydracoolSrpId = '';
        $getData = HydraCoolSrp::where('serial_number',$serialNumber)
        ->where('status',1)
        ->first();
        if(!empty($getData)){
            $gethydracoolSrpId = $getData['id'];
        }
        return $gethydracoolSrpId;
    }

    /**
    * Get hydracool srp unit by hydracool srp id
    */
    static function getHydracoolSrpUnitDetails($hydracoolSrpId){

        $getHydracoolSrpUnitListData = HydraCoolSrp::join('hydra_cool_srp_units as units','hydracool_srp.id','=','units.fk_hydracool_srp_id')
                                                    ->join('treatment_center as center','center.id','=','hydracool_srp.fk_treatment_centers_id')
                                                    ->select('units.title','units.id as handset_id','hydracool_srp.id as hydracoolsrp_id','hydracool_srp.serial_number','hydracool_srp.is_demo','hydracool_srp.fk_treatment_centers_id as treatment_center_id','center.full_company_name as treatment_center_name','hydracool_srp.manufacturer_name','hydracool_srp.manufacturing_date','hydracool_srp.sale_date')
                                                    ->where('hydracool_srp.id',$hydracoolSrpId)
                                                    ->where('units.status','1')
                                                    ->first();
        return $getHydracoolSrpUnitListData;

    }

    /**
    * Get Active hydracool srp and its units by treatment center id
    * @return obj
    */
    static function getHydraCoolSrpUnits($centerId,$searchData=null){
        $getHydracoolSrpUnitListData = HydraCoolSrp::join('hydra_cool_srp_units as units','hydracool_srp.id','=','units.fk_hydracool_srp_id');
        if(!empty($searchData)){
            $getHydracoolSrpUnitListData->select('hydracool_srp.serial_number','hydracool_srp.is_demo','units.title','hydracool_srp.status','hydracool_srp.created_at');
        }else{
            $getHydracoolSrpUnitListData->select('hydracool_srp.*','units.title');
        }
        $getHydracoolSrpUnitListData->where('hydracool_srp.fk_treatment_centers_id',$centerId);

        if(!empty($searchData)){
            foreach($searchData as $key=>$val){
                if($val){
                    if($key=="serial_number" || $key=="is_demo" || $key=="status"){
                        $getHydracoolSrpUnitListData->where('hydracool_srp.'.$key,'LIKE',"%".$val."%");
                    }else{
                        $getHydracoolSrpUnitListData->where('units.title','LIKE',"%".$val."%");
                    }
                }
            }
        }
        $getHydracoolSrpUnitListData->where('hydracool_srp.status','1');
        $getHydracoolSrpUnitListData->where('units.status','1');
        $getHydracoolSrpUnitListData->orderby('hydracool_srp.id','desc');

        if(!empty($searchData)){
            $getHydracoolSrpUnitListData = $getHydracoolSrpUnitListData->get()->toArray();
        }
        return $getHydracoolSrpUnitListData;
    }

    /**
    * Get Active hydracool srp and its units by treatment center id
    * @return obj
    */
    static function getHydraCoolSRPListWithTreatmentCenter($searchData=null){
        $authUser = Auth::user();
        $getHydracoolSrpUnitListData = TreatmentCenter::join('hydracool_srp as srp','srp.fk_treatment_centers_id','=','treatment_center.id')
        ->join('hydra_cool_srp_units as units','srp.id','=','units.fk_hydracool_srp_id')
        ->select('srp.*','units.title','treatment_center.full_company_name','treatment_center.id as treatmentcenter_id','treatment_center.is_ema');
        $getHydracoolSrpUnitListData->whereIn('srp.status',[1]);
        if($authUser->hasRole(['treatment centre manager'])){
            $getTreatmentCentreData = getGetTreatmentCentreCompanyDataByUserId();
            $getHydracoolSrpUnitListData->where('treatment_center.id',$getTreatmentCentreData->fk_treatment_center_id);
        }
        if($authUser->hasRole(['distributor principal']) || $authUser->hasRole(['distributor service']) || $authUser->hasRole(['distributor sales'])){
            $getDistributorCompanyData = getDistributorCompanyDataById();
            $getAllTreatmentCentreIds = getDistributorAlltreatmentCentreIds($getDistributorCompanyData->distributor_company_id);
            $treatmentCentreIdsVal = $getAllTreatmentCentreIds->pluck('id')->toArray();
            $getHydracoolSrpUnitListData->whereIn('treatment_center.id',$treatmentCentreIdsVal);
        }

        if(!empty($searchData)){
            foreach($searchData as $key=>$val){
                if($key=="full_company_name"){
                    $getHydracoolSrpUnitListData->where('treatment_center.'.$key,'LIKE',"%".$val."%");
                }else if($key=="serial_number"){
                    $getHydracoolSrpUnitListData->where('srp.'.$key,'LIKE',"%".$val."%");
                }else{
                    $getHydracoolSrpUnitListData->where('units.title','LIKE',"%".$val."%");
                }
            }
        }

        $getHydracoolSrpUnitListData->orderby('srp.id','desc');
        if(!empty($searchData)){
            $getHydracoolSrpUnitListData = $getHydracoolSrpUnitListData->get()->toArray();
        }
        return $getHydracoolSrpUnitListData;
    }
    /**
    * Get Active treatment center name by serial number
    * @return obj
    */
    static function getTreatmentCenterName($serialNumber){
        $getHydracoolSrpData =  TreatmentCenter::join('hydracool_srp','treatment_center.id','=','hydracool_srp.fk_treatment_centers_id')
        ->join('hydra_cool_srp_units as units','hydracool_srp.id','=','units.fk_hydracool_srp_id')
        ->select('units.title','units.id as handset_id','hydracool_srp.id as hydracoolsrp_id','hydracool_srp.serial_number','hydracool_srp.is_demo','hydracool_srp.fk_treatment_centers_id as treatment_center_id','treatment_center.full_company_name as treatment_center_name')
        ->where('treatment_center.status','1')
        ->where('hydracool_srp.status','1');
        $getHydracoolSrpData->where('hydracool_srp.serial_number',$serialNumber);
        $getHydracoolSrpData = $getHydracoolSrpData->get();

        return $getHydracoolSrpData;
    }

    /**
    * Get Active hydracool SRP units by serial number
    * @return obj
    */
    static function getHydraCoolSRPById($hydraCoolSRPID){
        $getHydracoolSrpData =  HydraCoolSrp::join('hydra_cool_srp_units as units','hydracool_srp.id','=','units.fk_hydracool_srp_id')
                                            ->select('units.title')
                                            ->where('units.status','1')
                                            ->where('hydracool_srp.id',$hydraCoolSRPID)
                                            ->get()
                                            ->toArray();
        return $getHydracoolSrpData;
    }

    static function getTreatmentCentreIdBySerialNumber($serialnumber){
        $getTreatmentCentreId =  HydraCoolSrp::select('fk_treatment_centers_id')
                                            ->where('status','1')
                                            ->where('serial_number',$serialnumber)
                                            ->first();
        return $getTreatmentCentreId->fk_treatment_centers_id;
    }
}
