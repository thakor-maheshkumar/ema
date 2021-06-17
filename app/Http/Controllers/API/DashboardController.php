<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\CoreSetting;
use App\AuditLog;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public $successStatus = 200;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $setting = CoreSetting::where('name','force_password_change')->first();

        if($setting->value == 1){
            if(Auth::user()->first_time_login == 1){
                if($request->ajax()){
                    $searchData =$request->get('search_data');
                    if(isset($searchData) && !empty($searchData)){
                        $getAuditLogData = AuditLog::getAuditLogData(null,$searchData,$isDashboard=1);
                        $data = makeNumericArray($getAuditLogData);
                        return $data;
                    }else{
                        $getAuditLogData = AuditLog::getAuditLogData(null,null,$isDashboard=1);
                        return Datatables::of($getAuditLogData)
                        ->addColumn('action', function ($audit_log) {
                        return '<a href="javascript:;" class="viewDetails" title="View Audit Log" data-audtiLogId='.$audit_log->id.'><i class="far fa-eye"></i></a>';
                        })
                        ->editColumn('name', function($getAuditLogData) {
                            if($getAuditLogData->module_name=="Email" && $getAuditLogData->name==""){
                                $getAuditLogData->name = 'Schedular';
                            }else{
                                if($getAuditLogData->name==""){
                                    $getAuditLogData->name = 'Anonymous user';
                                }
                            }
                            if($getAuditLogData->module_name=="Schedular"){
                                $getAuditLogData->name = 'Schedular';
                            }
                            return $getAuditLogData->name;
                        })->editColumn('company_name', function($getAuditLogData) {
                            if($getAuditLogData->company_name==""){
                                $getAuditLogData->company_name = '--';
                            }
                            if($getAuditLogData->module_name=="hydracool srp" || $getAuditLogData->module_name=="hydracool srp Device"){
                                $explodeData = explode('/',$getAuditLogData->company_name);
                                if(!empty($explodeData)){
                                    $getAuditLogData->company_name  = isset($explodeData[0]) ? $explodeData[0]  : '--';
                                }else{
                                    $getAuditLogData->company_name  = '--';
                                }
                            }
                            if($getAuditLogData->module_name=="Schedular"){
                                $getAuditLogData->company_name = 'Schedular';
                            }
                            return $getAuditLogData->company_name;
                        })
                        ->filterColumn('name', function($getAuditLogData, $keyword) {
                            $myString = "Auto Scheduler";
                            $Anonymous = "Anonymous";
                            if (strstr($myString,$keyword ) ) {
                                $getAuditLogData->where('audit_log.module_name','Email');
                                $getAuditLogData->whereNull('audit_log.user_id');
                            }else if (strstr($Anonymous,$keyword ) ) {
                                $getAuditLogData->where('audit_log.module_name','!=','Email');
                                $getAuditLogData->whereNull('audit_log.user_id');
                            }else{
                                $getAuditLogData->where('users.name','LIKE',"%".$keyword."%");
                            }
                        })
                        ->make(true);
                    }
                }else{
                    return view('dashboard');
                }
            }else{
                return redirect("/change_password");
            }
        }else{
            if($request->ajax()){
                $searchData =$request->get('search_data');
                if(isset($searchData) && !empty($searchData)){
                    $getAuditLogData = AuditLog::getAuditLogData(null,$searchData,$isDashboard=1);
                    $data = makeNumericArray($getAuditLogData);
                    return $data;
                }else{

                    $getAuditLogData = AuditLog::getAuditLogData(null,null,$isDashboard=1);
                    return Datatables::of($getAuditLogData)
                    ->addColumn('action', function ($audit_log) {
                    return '<a href="javascript:;" class="viewDetails" title="View Audit Log" data-audtiLogId='.$audit_log->id.'><i class="far fa-eye"></i></a>';
                    })
                    ->editColumn('name', function($getAuditLogData) {
                        if($getAuditLogData->module_name=="Email" && $getAuditLogData->name==""){
                            $getAuditLogData->name = 'Auto Scheduler';
                        }else{
                            if($getAuditLogData->name==""){
                                $getAuditLogData->name = 'Anonymous';
                            }
                        }
                        if($getAuditLogData->module_name=="hydracool srp" || $getAuditLogData->module_name=="hydracool srp Device"){
                            $explodeData = explode('/',$getAuditLogData->company_name);
                            if(!empty($explodeData)){
                                $getAuditLogData->name  = isset($explodeData[1]) ? $explodeData[1] : '--';
                            }else{
                                $getAuditLogData->name  = '--';
                            }
                        }
                        return $getAuditLogData->name;
                    })->editColumn('company_name', function($getAuditLogData) {
                        if($getAuditLogData->company_name==""){
                            $getAuditLogData->company_name = '--';
                        }
                        if($getAuditLogData->module_name=="hydracool srp" || $getAuditLogData->module_name=="hydracool srp Device"){
                            $explodeData = explode('/',$getAuditLogData->company_name);
                            if(!empty($explodeData)){
                                $getAuditLogData->company_name  = isset($explodeData[0]) ? $explodeData[0]  : '--';
                            }else{
                                $getAuditLogData->company_name  = '--';
                            }
                        }
                        return $getAuditLogData->company_name;
                    })
                    ->filterColumn('name', function($getAuditLogData, $keyword) {
                        $myString = "Auto Scheduler";
                        $Anonymous = "Anonymous";
                        if (strstr($myString,$keyword ) ) {
                            $getAuditLogData->where('audit_log.module_name','Email');
                            $getAuditLogData->whereNull('audit_log.user_id');
                        }else if (strstr($Anonymous,$keyword ) ) {
                            $getAuditLogData->where('audit_log.module_name','!=','Email');
                            $getAuditLogData->whereNull('audit_log.user_id');
                        }else{
                            $getAuditLogData->where('users.name','LIKE',"%".$keyword."%");
                        }
                    })
                    ->make(true);
                }
            }else{
                return view('dashboard');
            }
        }

    }

    public function redirectDashboard(){

        return redirect("/dashboard");
    }

    /* get Permission to role */
        public function assignPermissionToRole($roleId=null){
            $getAlreadyAssignPermissionToRole = array();
            if($roleId){
                $role = Role::find($roleId);
                if(empty($role)){
                    $notification = array(
                        'message' => 'No such role found',
                        'alert-type' => 'error'
                    );
                    return redirect()->route('assign-permission')->with($notification);
                }else{
                    $getAlreadyAssignPermissionToRole = $role->getAllPermissions();
                    $getAlreadyAssignPermissionToRole = $getAlreadyAssignPermissionToRole->pluck('id')->toArray();
                }
            }
            $getRolesData = Role::get()->toArray();
            $getAllPermissionData = Permission::get()->toArray();
            return view('assign-permission',compact('getRolesData','roleId','getAllPermissionData','getAlreadyAssignPermissionToRole'));
        }
    /* get Permission to role */


     /* Assign Permission to role */
     public function updatePermissionToRole(Request $request){
        $roleId = $request->input('role_id');
        $role = Role::find($roleId);
        if(empty($role)){
            $notification = array(
                'message' => 'No such role found',
                'alert-type' => 'error'
            );
            return redirect()->route('assign-permission')->with($notification);
        }else{
            $getAllPermission = $request->input('permission_val');
            $permissions = Permission::find($getAllPermission);
            $role->syncPermissions($permissions);
            $notification = array(
                'message' => 'Permission updated successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('assign-permission')->with($notification);
        }
    }
    /* Assign Permission to role */

    public function setTimeZone(Request $request){
        $getTimeZone = $request->input('timezone');
        $timezone_value = $request->input('timezone_value');
        session(['user_timezone' => $getTimeZone]);
        session(['user_timezone_value' => $timezone_value]);
    }

}

