<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\AuditLog;
use Yajra\DataTables\Facades\DataTables;
use App\Country;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /* Get country data */
            $getCountryData = Country::orderBy('name')->get();
         /* Get country data */

        return view('welcome',compact('getCountryData'));
    }


    public function rolesandpermission(){
        $role = Role::all();
        return view('roles-permission')->with('roles',$role);
    }

    /**
    *   Add User menu
    */
    public function addUser(Request $request)
    {


    }

    /**
    *   create creatment center
    */
    public function addTreatmentCenter()
    {
        return view('add-treatment');
    }

    /**
    *   Load the audit log data
    *   @return json
    */
    public function getAuditLogData(Request $request)
    {
        if($request->ajax()){
            $searchData =$request->get('search_data');
            if(isset($searchData) && !empty($searchData)){
                $getAuditLogData = AuditLog::getAuditLogData(null,$searchData);
                $data = makeNumericArray($getAuditLogData);
                return $data;
            }else{

                $getAuditLogData = AuditLog::getAuditLogData();
                return Datatables::of($getAuditLogData)
                ->addColumn('action', function ($audit_log) {
                return '<a href="javascript:;" class="viewDetails" title="View Audit Log" data-audtiLogId='.$audit_log->id.'><i class="far fa-eye"></i></a>';
                })
                ->editColumn('name', function($getAuditLogData) {
                    if($getAuditLogData->module_name=="Email" && $getAuditLogData->name==""){
                        $getAuditLogData->name = 'Scheduler';
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
                    if($getAuditLogData->module_name=="hydracool srp" || $getAuditLogData->module_name=="hydracool srp Device" || $getAuditLogData->module_name=="HydraCool SRP Device" || $getAuditLogData->module_name=="Email"){
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
                ->filterColumn('created_at', function($getAuditLogData, $keyword) {
                    $dateBetween = explode('to',$keyword);
                        if(!empty($dateBetween)){
                            $from_date = date('Y-m-d',strtotime(str_replace('\-','-', $dateBetween[0])));
                            $to_date = date('Y-m-d',strtotime(str_replace('\-','-', $dateBetween[1])));
                            $getAuditLogData->whereRaw("date(CONVERT_TZ(`audit_log`.`created_at`,'+00:00','".session('user_timezone_value')."')) >= '" . $from_date . "' AND date(CONVERT_TZ(`audit_log`.`created_at`,'+00:00','".session('user_timezone_value')."')) <= '" . $to_date . "'");
                        }
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
            return view('audit-log');
        }
    }

    /**
    *   get the audit log detail by id
    *   @return json
    */
    public function getAuditLogDetail(Request $request)
    {
        $input = $request->all();
        if(isset($input['id']) && !empty($input['id'])){
            $checkDataAvailable = AuditLog::find($input['id']);
            if(!empty($checkDataAvailable)){
                $getAuditLogData = AuditLog::getAuditLogDetails($input['id']);

                 $data['success'] = '1';
                 $data['audit_log_detail'] = $getAuditLogData;
            }else{
                $data['success'] = '0';
                $data['message'] = 'No Record Found';
            }
        }else{
            $data['success'] = '0';
            $data['message'] = 'Please enter the log id';
        }
        echo json_encode($data);
    }
}
