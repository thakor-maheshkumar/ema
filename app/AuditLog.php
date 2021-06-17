<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AuditLog extends Model
{
    protected $table = 'audit_log';
    protected $appends = ['created_date'];

    public function getCreatedDateAttribute()
    {
         $timestamp = $this->created_at;
         $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp);
         $date->setTimezone(session('user_timezone'));
         return date('d-m-Y',strtotime($date->toDateString()));
    }

    public function getAuditTimestampAttribute()
    {
         $timestamp = $this->created_at;
         $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp);
         return $date->toTimeString();
    }
    public function scopeAuditcreate($query,$keyword)
    {
        $dateBetween = explode('to',$keyword);
        if(!empty($dateBetween)){
            $from_date = date('Y-m-d',strtotime(str_replace('\-','-', $dateBetween[0])));
            $to_date = date('Y-m-d',strtotime(str_replace('\-','-', $dateBetween[1])));
            return $query->whereRaw("date(audit_log.created_at) >= '" . $from_date . "' AND date(audit_log.created_at) <= '" . $to_date . "'");
        }
        // return $query->where(\DB::raw('DATE(`audit_log`.`created_at`)'), 'like', '%'.$keyword.'%');
    }
    public function scopeAudittime($query,$keyword)
    {
        return $query->where(\DB::raw('TIME(`audit_log`.`created_at`)'), 'like', '%'.$keyword.'%');
    }


    /**
    * Get audit log data
    * @return obj  auditlog with user data
    */
    static function getAuditLogData($id=null,$searchData=null,$isDashboard=null){
            $authUser = Auth::user();
            $auditLogData = AuditLog::leftjoin('users','users.id','=','audit_log.user_id');
                                if(empty($searchData)){
                                    $auditLogData->select('users.name','users.username','audit_log.*');
                                }
                                if(!empty($id)){
                                    $auditLogData->where('audit_log.id',$id);
                                }

                                if(!empty($searchData)){
                                    foreach($searchData as $key=>$val){
                                        if($val){
                                            if($key=="name"){
                                                    $auditLogData->orwhere($key,"".$val."");
                                            }elseif($key=="created_at"){
                                                $auditLogData->auditcreate($val);
                                            }elseif($key=="audit_timestamp"){
                                                $auditLogData->audittime($val);
                                            }else{
                                                $auditLogData->where('audit_log.'.$key,'LIKE',"%".$val."%");
                                            }
                                        }
                                    }
                                }
                            if(!empty($isDashboard)){
                                $auditLogData->where('audit_log.created_at', '>=', Carbon::now()->subDay()->toDateTimeString());
                            }

                            if(!$authUser->hasRole(['system administrator'])){
                                if($authUser->hasRole(['distributor principal','distributor service'])){

                                    /* Get  Distributor company id and Distributor Users*/
                                        $getDistributorCompanyData = getDistributorCompanyDataById();
                                        $getAllUsersId = getDistributorCompanyAllUsersByCompanyId($getDistributorCompanyData->distributor_company_id);
                                    /* Get  Distributor company id and Distributor Users*/


                                    /* Get Distributor All treatment centre Id and that treatment centre user id*/
                                        $getAllTreatmentcentreByDistributorId = getDistributorAlltreatmentCentreIds($getDistributorCompanyData->distributor_company_id);
                                        if(!empty($getAllTreatmentcentreByDistributorId)){
                                            $getTreatmentCentreIds = $getAllTreatmentcentreByDistributorId->pluck('id')->toArray();
                                            $getAllTreatmentCentreUsers = getAllUsersByTreatmentcentreId($getTreatmentCentreIds);
                                            $getAllUsersId = array_merge($getAllUsersId,$getAllTreatmentCentreUsers);
                                        }
                                    /* Get Distributor All treatment centre Id and that treatment centre user id*/

                                    if(!empty($getAllUsersId)){
                                        $auditLogData->whereIn('audit_log.user_id',$getAllUsersId);
                                    }
                                }
                            }

                            $auditLogData->orderby('audit_log.id','desc');
                           if(!empty($searchData)){
                                $auditLogData = $auditLogData->select('users.name','audit_log.*')->get();
                                $newData = [];
                                foreach($auditLogData as $key=>$val){

                                    $date = Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at);
                                    $date->setTimezone(session('user_timezone'));
                                    $created_at = date('d-m-Y',strtotime($date->toDateString()));

                                    $newData[$key][0]= $created_at;

                                    $date = Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at);
                                    $date->setTimezone(session('user_timezone'));
                                    $audit_timestamp =  $date->toTimeString();

                                    $newData[$key][1] =  $audit_timestamp;

                                    if($val->module_name=="Email" && $val->name==''){
                                        $name = 'Auto Scheduler';
                                    }else if($val->name==''){
                                        $name = 'Anonymous';
                                    }else{
                                        $name = $val->name;
                                    }

                                    if($val->company_name==""){
                                        $companyName = '--';
                                    }else{
                                        $companyName = $val->company_name;
                                    }

                                    if($val->module_name=="hydracool srp" || $val->module_name=="hydracool srp Device" || $val->module_name=="HydraCool SRP Device"){
                                        $explodeData = explode('/',$val->company_name);
                                        if(!empty($explodeData)){
                                            $name  = isset($explodeData[1]) ? $explodeData[1] : '--';
                                            $companyName  = isset($explodeData[0]) ? $explodeData[0]  : '--';
                                        }else{
                                            $name  = '--';
                                            $companyName  = '--';
                                        }
                                    }

                                    $newData[$key][2]=$name;
                                    $newData[$key][3]=$companyName;
                                    $newData[$key][4]= $val->module_activity;
                                    $newData[$key][5]=$val->description;

                                }

                                return $newData;
                            }else{
                                return $auditLogData;
                            }

    }

    static function getAuditLogDetails($id){
        $getData = AuditLog::where('id',$id)->first();
        return $getData;
    }
}
