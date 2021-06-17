<?php

use App\AuditLog;
use App\TreatmentCenterUser;
use App\DistributorUser;
use App\TreatmentCenter;
use App\SMSTemplate;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;
use App\GetUserIdByRoleId;
use App\User;
use App\Mail\SendDynamicEmail;

/**
*  @moduleName on which module is activity is perform
*  @moduleActivity which activity is perform like add,update,delete
*  @description on module is activity is perform by for which user or which module like  admin has updated the user name
*  @requestData module activity request data in post,get any
*/

if (!function_exists('captureAuditLog')) {

    function captureAuditLog($moduleName,$moduleActivity,$description=null,$requestData,$companyName=null){
        $responce = array();
        try{
            $auditLog = new AuditLog;
            if(isset(Auth::user()->id) && !empty(Auth::user()->id)){
             $auditLog->user_id = Auth::user()->id;
            }else{

                if(!empty($requestData) && array_key_exists('user_id',$requestData)){
                    if($requestData['user_id']!=0){
                        $auditLog->user_id = $requestData['user_id'];
                    }
                }
            }
            $auditLog->module_name   = $moduleName;
            $auditLog->module_activity = $moduleActivity;
            $auditLog->description  = $description;
            $auditLog->company_name = $companyName;
            $auditLog->request_data = json_encode($requestData);
            $auditLog->ip_address = request()->ip();
            $auditLog->audit_timestamp = date('H:i:s');
            $auditLog->save();
            $responce['status']='1';
            $responce['message']='audit log capture successfully.';
            return $responce;
        } catch (Exception $e) {
            $responce['status']='0';
            $responce['message']= $e->getMessage();
            return $responce;
        }
    }
}
/**
* get total record of table
* @return number
*/

if (!function_exists('getTotalRecords')) {
    function getTotalRecords($tableName) {
        $getTotalCount = DB::table($tableName)->count();
        $incrementedCount = $getTotalCount+1;
        $returnData = array('total_records'=>$getTotalCount,"incremented_record"=>$incrementedCount);
        return $returnData;
    }
}

/**
* generate internal id
* @return number
*/
if (!function_exists('generateInternalId')) {
    function generateInternalId($number) {
        $generatenumber = str_pad($number,5,"0",STR_PAD_LEFT);
        return $generatenumber;
    }
}

/**
*  Download the audit log data excel/csv
*  @return downloadfile
*/
if (!function_exists('makeNumericArray')) {
   function makeNumericArray($tableCollection){
        $data = array();
        $data=array_map(function($item){
            return  array_values($item);
        },$tableCollection);
        return $data;
    }
}

if (!function_exists('getFirstCharacter')) {
   function getFirstCharacter(){
        $name = Auth::user()->name;;
        preg_match_all('/\b\w/', $name, $Result);
        return implode("", $Result[0]);
    }
}

if (!function_exists('getUserStatus')) {

    function getUserStatus($value) {

        $getUserStatus = array(
            '1'=>'Active',
            '2'=>'Deleted',
            '3'=>'Suspended',
        );
        $data = $getUserStatus[$value];
        return $data;
    }
}

if (!function_exists('getUserRoles')) {

    function getUserRoles($value) {

        $getUserRoles = array(
            'system administrator' => 'System Administrator',
            'ema analyst' => 'EMA Analyst',
            'ema service support' => 'EMA Service Support',
            'treatment centre manager' => 'Treatment Centre Principal',
            'distributor sales' => 'Distributor Sales',
            'distributor service' => 'Distributor Service',
            'distributor principal' => 'Distributor Principal',
        );
        $data = $getUserRoles[$value];
        return $data;
    }
}


if (!function_exists('getRoleWiseURLAndMenuAccess')) {
    function getRoleWiseURLAndMenuAccess() {
        $authUser = Auth::user();
        if($authUser->hasRole(['system administrator']) || $authUser->hasRole(['ema service support'])){
            $data = array(
                    'EMA_USERS'=>route("ema_users"),
                    'DISTRIBUTOR'=>route("distributor"),
                    'TREATMENTCENTRE_LIST'=>route("treatment-centre-list"),
                    'DEVICE'=>route("devices"),
                    'TREATMENT_CENTRE_FILE_DATA'=>route("list-treatmentcentre-file"),
                    'DIAGNOSTIC_DATA'=>route("diagnosticData"),
                    'AUDIT_LOG'=>route("audit-list"),
                    'MEDIA_LIBRARY'=>route("media-library"),
                    'REPORTS'=>'Add Route Here',
            );
        }
        if($authUser->hasRole(['ema service support'])){
            $data = array(
                    'EMA_USERS'=>route("ema_users"),
                    'DISTRIBUTOR'=>route("distributor"),
                    'TREATMENTCENTRE_LIST'=>route("treatment-centre-list"),
                    'DEVICE'=>route("devices"),
                    'DIAGNOSTIC_DATA'=>route("diagnosticData"),
                    'AUDIT_LOG'=>route("audit-list"),
                    'MEDIA_LIBRARY'=>route("media-library"),
                    'REPORTS'=>'Add Route Here',
            );
        }
        if($authUser->hasRole(['ema analyst'])){
            $data = array(
                    'EMA_USERS'=>route("ema_users"),
                    'DISTRIBUTOR'=>route("distributor"),
                    'TREATMENTCENTRE_LIST'=>route("treatment-centre-list"),
                    'DEVICE'=>route("devices"),
                    'TREATMENT_CENTRE_FILE_DATA'=>route("list-treatmentcentre-file"),
                    'DIAGNOSTIC_DATA'=>route("diagnosticData"),
                    'MEDIA_LIBRARY'=>route("media-library"),
                    'REPORTS'=>'Add Route Here',
            );
        }else if($authUser->hasRole(['treatment centre manager'])){
            $getGetTreatmentCentreCompanyData = getGetTreatmentCentreCompanyDataByUserId();
            $data = array(
                'TREATMENTCENTRE_LIST'=>route('view-treatment-center',['id'=>$getGetTreatmentCentreCompanyData->fk_treatment_center_id]),
                'DEVICE'=>route("devices"),
                'TREATMENT_CENTRE_FILE_DATA'=>route("list-treatmentcentre-file"),
                'DIAGNOSTIC_DATA'=>route("diagnosticData"),
                // 'AUDIT_LOG'=>route("audit-list"),
                'MEDIA_LIBRARY'=>route("media-library"),
                'REPORTS'=>'Add Route Here',
            );
        }else if($authUser->hasRole(['distributor sales'])){
        $getDistributorData = getDistributorCompanyDataById();
        $data = array(
                'DISTRIBUTOR'=>route('distributor-list',['internal_id'=>$getDistributorData->internal_id]),
                'TREATMENTCENTRE_LIST'=>route("treatment-centre-list"),
                'DEVICE'=>route("devices"),
                'TREATMENT_CENTRE_FILE_DATA'=>route("list-treatmentcentre-file"),
                'DIAGNOSTIC_DATA'=>route("diagnosticData"),
                // 'AUDIT_LOG'=>route("audit-list"),
                'MEDIA_LIBRARY'=>route("media-library"),
                'REPORTS'=>'Add Route Here',
            );
        }else if($authUser->hasRole(['distributor service'])){
        $getDistributorData = getDistributorCompanyDataById();
        $data = array(
                'DISTRIBUTOR'=>route('distributor-list',['internal_id'=>$getDistributorData->internal_id]),
                'TREATMENTCENTRE_LIST'=>route("treatment-centre-list"),
                'DEVICE'=>route("devices"),
                'DIAGNOSTIC_DATA'=>route("diagnosticData"),
                'AUDIT_LOG'=>route("audit-list"),
                'MEDIA_LIBRARY'=>route("media-library"),
                'REPORTS'=>'Add Route Here',
            );
        }else if($authUser->hasRole(['distributor principal'])){
        $getDistributorData = getDistributorCompanyDataById();
        $data = array(
                'DISTRIBUTOR'=>route('distributor-list',['internal_id'=>$getDistributorData->internal_id]),
                'TREATMENTCENTRE_LIST'=>route("treatment-centre-list"),
                'DEVICE'=>route("devices"),
                'TREATMENT_CENTRE_FILE_DATA'=>route("list-treatmentcentre-file"),
                'DIAGNOSTIC_DATA'=>route("diagnosticData"),
                'AUDIT_LOG'=>route("audit-list"),
                'MEDIA_LIBRARY'=>route("media-library"),
                'REPORTS'=>'Add Route Here',
            );
        }

        return $data;
    }
}

if (!function_exists('SendTwilioSMS')) {

    function SendTwilioSMS($recipient, $template_slug) {
        try {
            $template = SMSTemplate::where('slug', $template_slug)->first();

            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_AUTH_TOKEN");
            $twilio_number = getenv("TWILIO_NUMBER");
            $client = new Client($account_sid, $auth_token);
            $respose = $client->messages->create($recipient, ['from' => $twilio_number, 'body' => $template->content]);

            $resonse_array = array();
            $resonse_array['sid'] = $respose->sid;
            $resonse_array['body'] = $respose->body;
            $resonse_array['numSegments'] = $respose->numSegments;
            $resonse_array['direction'] = $respose->direction;
            $resonse_array['from'] = $respose->from;
            $resonse_array['to'] = $respose->to;
            $resonse_array['errorMessage'] = $respose->errorMessage;
            $resonse_array['uri'] = $respose->uri;
            $resonse_array['accountSid'] = $respose->accountSid;
            $resonse_array['status'] = $respose->status;
            $resonse_array['errorCode'] = $respose->errorCode;
            $resonse_array['dateUpdated'] = $respose->dateUpdated;

            $moduleName = 'Send SMS';
            $moduleActivity = 'Send SMS :'.$template->name;
            $description = 'SMS send on '.$recipient;

            /*Start - Add action in audit log*/
            captureAuditLog($moduleName,$moduleActivity,$description,$resonse_array);
            /*End - Add action in audit log*/

            return $resonse_array;

        } catch (\Exception $e) {
            // $notification = array(
            //     'message' => $e->getMessage(),
            //     'alert-type' => 'error'
            // );
            // return redirect()->back()->with($notification);
        }
    }
}

if (!function_exists('getUserOnline')) {

    function getUserOnline($value) {

        $getUserOnline = array(
            '0'=>'No',
            '1'=>'Yes',
        );
        $data = $getUserOnline[$value];
        return $data;
    }
}

/*Get Original Data*/
if (!function_exists('getOriginalData')) {
    function getOriginalData($modelId) {
        $getOriginalData = $modelId->getOriginal();
        return $getOriginalData;
    }
}

/* Get All permission assign to roles */
if (!function_exists('getAllPermissionViaRoles')) {
    function getAllPermissionViaRoles() {
        $user = Auth::user();
        $roleId = $user->roles->pluck('id');
        $role = Role::find($roleId[0]);
        $getAllPermission = $user->getAllPermissions();
        $getAllPermission->pluck('name')->toArray();
        return $getAllPermission;
    }
}
/* Add Http or https if not present in value */
if (!function_exists('addhttp')) {
    function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }
}

/* Add Http or https if not present in value */
if (!function_exists('addSymbol')) {
    function addSymbol($number) {
        $number = str_replace("+", "", $number);
        return '+'.$number;
    }
}


/*Get all users id by role id*/
if (!function_exists('getroleIdByUserId')) {
    function getroleIdByUserId($userId) {
        $getAllUsers = GetUserIdByRoleId::select('role_id')->where('model_id',$userId)->get();
        return $getAllUsers->pluck('role_id')->toArray();
    }
}



if (!function_exists('getGetTreatmentCentreCompanyDataByUserId')) {
    function getGetTreatmentCentreCompanyDataByUserId($sessionId = null) {
        if(!empty($sessionId)){
            $userId = $sessionId;
        }else{
            $authUser = Auth::user();
            $userId = $authUser->id;
        }

        /* Get Current User treatment centre company data*/
        $getTreatmentCentreData = TreatmentCenterUser::join('treatment_center as t','t.id','=','treatment_center_user_mapping.fk_treatment_center_id')
                                                    ->select('treatment_center_user_mapping.fk_treatment_center_id','t.full_company_name')
                                                    ->where('treatment_center_user_mapping.fk_user_id',$userId)
                                                    ->first();
        /* Get Current User treatment centre company data*/
        return $getTreatmentCentreData;
    }
}

if (!function_exists('getDistributorAlltreatmentCentreIds')) {
    function getDistributorAlltreatmentCentreIds($companyId) {
        $authUser = Auth::user();
        $getUserRoleId = $authUser->roles->pluck('id')->toArray();

        /* Get Current User treatment centre company data*/
        $getTreatmentCentreIds = TreatmentCenter::select('id')
                                                 ->where('distributors',$companyId)
                                                 ->where('status',1)
                                                 ->get();
        /* Get Current User treatment centre company data*/
        return $getTreatmentCentreIds;
    }
}


if (!function_exists('getDistributorCompanyDataById')) {
    function getDistributorCompanyDataById($sessionId = null) {
        if(!empty($sessionId)){
            $userId = $sessionId;
        }else{
            $authUser = Auth::user();
            $userId = $authUser->id;
        }

        /* Get Current User distributor company data*/
        $getDistributorCompanyData = DistributorUser::join('distributors as d','d.id','=','distributor_user_mapping.fk_distributor_id')
                                                    ->select('d.internal_id','d.id as distributor_company_id','d.full_company_name')
                                                    ->where('distributor_user_mapping.fk_user_id',$userId)
                                                    ->first();
        /* Get Current User distributor company data*/
        return $getDistributorCompanyData;
    }
}


/*Get all distributor company's userid */
if (!function_exists('getDistributorCompanyAllUsersByCompanyId')) {
    function getDistributorCompanyAllUsersByCompanyId($id) {
        $getDistributorCompanyUsersData = DistributorUser::join('users','users.id','=','distributor_user_mapping.fk_user_id')
                                                          ->select('fk_user_id')
                                                          ->where('fk_distributor_id',$id)
                                                          ->whereIn('users.status',[1,3])
                                                          ->get();
        return $getDistributorCompanyUsersData->pluck('fk_user_id')->toArray();
    }
}

/*Get all users id by role id*/
if (!function_exists('getUsersIdByRolesId')) {
    function getUsersIdByRolesId($roleId) {
        $getAllUsers = GetUserIdByRoleId::select('model_id')->where('role_id',$roleId)->get();
        return $getAllUsers->pluck('model_id')->toArray();
    }
}

/*Get all treatment centre company's userid */
if (!function_exists('getAllUsersByTreatmentcentreId')) {
    function getAllUsersByTreatmentcentreId($treatmentCentreID) {
        $getTreatmentCentreCompanyUsersData = TreatmentCenterUser::join('users','users.id','=','treatment_center_user_mapping.fk_user_id')
                                                                ->select('fk_user_id')
                                                                ->whereIn('fk_treatment_center_id',$treatmentCentreID)
                                                                ->whereIn('users.status',[1,3])
                                                                ->get();
        return $getTreatmentCentreCompanyUsersData->pluck('fk_user_id')->toArray();
    }
}

/* Get User Company name by username */
if (!function_exists('getUserCompanyName')) {
    function getUserCompanyName($username) {
        $getUserRole =$username->roles->first()->name;
        if($getUserRole=="distributor principal" || $getUserRole=="distributor service" || $getUserRole=="distributor sales"){
           $data =  getDistributorCompanyDataById($username->id);
           $companyName = $data->full_company_name;
        }else if($getUserRole=="treatment centre manager"){
            $data = getGetTreatmentCentreCompanyDataByUserId($username->id);
            $companyName = $data->full_company_name;
        }else{
            $companyName = EMA_COMPANY_NAME;
        }
        return ucfirst($companyName);
    }
}

/* Suspend or release all company users */
if (!function_exists('suspendOrReleseCompanyUsers')) {
    function suspendOrReleseCompanyUsers($getAllUsers,$action) {
        foreach($getAllUsers as $userId){
            $getUserData = User::where('id',$userId)->whereIn('status',[1,3])->with('roles')->first();
            if(!empty($getUserData)){

                if($action=="suspend"){
                    /* Capture audit log for all company users has been suspended */
                            $usercompanyName = getUserCompanyName(Auth::user());
                            $moduleName = 'user';
                            $moduleActivity = 'Suspended user';
                            $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has Suspended '.ucfirst($getUserData->name)." (".getUserRoles($getUserData->roles->first()->name).").";
                            $requestData = array('user_id'=>$userId);

                            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                    /* Capture audit log for all company users has been suspended */

                    /* Send suspend email to all company users and capture the audit log for email send*/
                            $data['slug'] = 'suspend_user_account';
                            $data['name'] = $getUserData->name;
                            Mail::to($getUserData->email)->queue(new SendDynamicEmail($data));

                            $moduleName = 'Email';
                            $moduleActivity = 'Email logged for suspended user';
                            $description = 'Email has been sent to Suspended user : '.ucfirst($getUserData->name);
                            $requestData = array('user_id'=>$userId);
                                captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                    /* Send suspend email to all company users and capture the audit log for email send*/
                }else{
                    /* Capture audit log for all company users has been suspended */
                        $usercompanyName = getUserCompanyName(Auth::user());
                        $moduleName = 'user';
                        $moduleActivity = 'Released user';
                        $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has Released '.ucfirst($getUserData->name)." (".getUserRoles($getUserData->roles->first()->name).").";
                        $requestData = array('user_id'=>$userId);

                        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                    /* Capture audit log for all company users has been suspended */

                    /* Send suspend email to all company users and capture the audit log for email send*/
                        $data['slug'] = 'release_user_account';
                        $data['name'] = $getUserData->name;
                        Mail::to($getUserData->email)->queue(new SendDynamicEmail($data));

                        $moduleName = 'Email';
                        $moduleActivity = 'Email logged for Released user';
                        $description = 'Email has been sent to Released user : '.ucfirst($getUserData->name);
                        $requestData = array('user_id'=>$userId);
                            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                    /* Send suspend email to all company users and capture the audit log for email send*/
                }
            }
       }
    }
}

/* Get User Company name by username */
if (!function_exists('getUserCompanyType')) {
    function getUserCompanyType($username) {
        $getUserRole =$username->roles->first()->name;
        if($getUserRole=="distributor principal" || $getUserRole=="distributor service" || $getUserRole=="distributor sales"){
           $companyType = "Distributor";
        }else if($getUserRole=="treatment centre manager"){
            $companyType = "Treatment Centre";
        }else{
            $companyType = EMA_COMPANY_NAME;
        }
        return ucfirst($companyType);
    }
}

/* Get User Company name by username */
if(!function_exists('checkTreatmentCentreISEMA')) {
    function checkTreatmentCentreISEMA($centreID) {
        $checkTreatmentCentreType  = TreatmentCenter::select('is_ema')->where('id',$centreID)->first();

        return $checkTreatmentCentreType['is_ema'];
    }
}

/* convert value in hours and second */
if(!function_exists('convertInHoursSecords')) {
    function convertInHoursSecords($minutes) {
        if(!empty($minutes) && $minutes!=="--"){
            $hours =  floor($minutes / 60).':'.($minutes -   floor($minutes / 60) * 60);
            $seconds =  $minutes * 60;
            $return  = array($hours,$seconds);
        }else{
            $return  = array(0,0);
        }
        return $return;
    }
}

if(!function_exists('jsonDataShortCode')) {
    function jsonDataShortCode($code,$childArray = null) {
        $codes = array(
            'json_type'=>array(
                'T'=>'Treatment',
                'B'=>'Boot',
                'S'=>'System',
                ),
            'treatment_status'=>array(
                'SU'=>'Successful',
                'CR'=>'Critical Error',
                'CA'=>'Cancelled'
            ),
            'mod'=>array(
                'HY'=>'Hydration',
                'AA'=>'Anti-Aging',
                'CM'=>'Custom Mode',
                'CD'=>'Cell Division'
            ),
            'skin_type'=>array(
                'NO'=>'Normal',
                'DR'=>'DRY',
                'CO'=>'Combination',
                'OI'=>'Oily'
            ),
            'skin_condition'=>array(
                'DE'=>'Dehydrated',
                'SE'=>'Sensitive',
                'PI'=>'Pigmented',
                'AG'=>'Aging',
                'CO'=>'Congested',
            ),
            'body_parts'=>array(
                'NE'=>'Neck',
                'EY'=>'Eyes',
                'FA'=>'Face',
                'DE'=>'Decollete',
                'HA'=>'Hands'
            ),
            'technology'=>array(
                'AQ'=>'AquaB',
                'VI'=>'VibroX',
                'MI'=>'MicroT',
                'CO'=>'Collagen+',
                'UL'=>'UltraB'
            ),
        );
        return isset($codes[$childArray][$code]) ? $codes[$childArray][$code] : $code;
    }
}

/* convert value in hours and second */
if(!function_exists('getFirstCharacterFromString')) {
    function getFirstCharacterFromString($data) {
        $firstcharacter = $data[0];
        return $firstcharacter;
    }
}


if(!function_exists('getModeName')) {
    function getModeName($mode) {
        $name='';
        if(!empty($mode)){
            if($mode==1){
                $name = "Fresh";
            }else{
                $name = "Bright";
            }
        }
        return $name;
    }
}

?>

