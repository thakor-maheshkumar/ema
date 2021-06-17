<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CoreSetting;
use Illuminate\Support\Facades\Validator;
use Auth;

class CoreSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator=Validator::make($request->all(),[
            'name'=>['required'],
            'value'=>['required'],
            'created_by'=>['required'],
            'ip_address'=>['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $coreSetting = new CoreSetting();
        $coreSetting->name=$request->name;
        $coreSetting->value=$request->value;
        $coreSetting->created_by=$request->created_by;
        $coreSetting->ip_address=$request->ip_address;
        $coreSetting->save();

        $moduleName = 'core setting';
        $moduleActivity = 'Added Coresetting';
        $description =$coreSetting['name'].' has been added';
        $usercompanyName = getUserCompanyName(Auth::user());

        /*Add action in audit log*/
            captureAuditLog($moduleName,$moduleActivity,$description,$request->all(),$usercompanyName);
        /*Add action in audit log*/

        return response()->json(['status'=>'Added successfully','last_inserted_id'=>$coreSetting->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id=null)
    {
        //
        if($id)
        {
            $coreSetting=CoreSetting::find($id);
            return response()->json(['data'=>$coreSetting]);

            if($id==null){
                return response()->json(['data'=>'not']);
            }
        }
        else
        {
            $coreSetting=CoreSetting::all();
            return response()->json(['data'=>$coreSetting]);
        }



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator=Validator::make($request->all(),[
            'name'=>['required'],
            'value'=>['required'],
            'created_by'=>['required'],
            'ip_address'=>['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $coreSetting=CoreSetting::find($id);
        $coreSetting->name=$request->name;
        $coreSetting->value=$request->value;
        $coreSetting->created_by=$request->created_by;
        $coreSetting->ip_address=$request->ip_address;
        $coreSetting->save();

        $moduleName = 'core setting';
        $moduleActivity = 'Updated Coresetting';
        $description = getUserRoles(Auth::user()->roles->first()->name).' has added ' .$coreSetting['name'];
        $usercompanyName = getUserCompanyName(Auth::user());
        /*Add action in audit log*/

            captureAuditLog($moduleName,$moduleActivity,$description,$request->all(),$usercompanyName);
        /*Add action in audit log*/

        return response()->json(['status'=>'Update successfully','last_inserted_id'=>$coreSetting->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $coreSetting=CoreSetting::find($id);
        $name=$coreSetting->name;
        $coreSetting->status=0;
        $coreSetting->save();

        $moduleName = 'core setting';
        $moduleActivity = 'Delete Coresetting';
        $description = getUserRoles(Auth::user()->roles->first()->name).' has deleted'.$name;
        $usercompanyName = getUserCompanyName(Auth::user());
        $settingArray = array('setting_id'=>$id);

        /*Add action in audit log*/
        captureAuditLog($moduleName,$moduleActivity,$description,$settingArray,$usercompanyName);
        /*Add action in audit log*/

        return response()->json(['status'=>'Delete successfully']);
    }

    public function updateCoreSetting(Request $request){
        $validator=Validator::make($request->all(),[
            'inactivity_session_time'=>['required'],
            'login_attempt'=>['required'],
            'inactive_user_suspension'=>['required'],
            'delete_suspended_user'=>['required']
        ]);
        $force_password_change = '0';
        if(isset($request->force_password_change) && $request->force_password_change == 'on'){
            $force_password_change = '1';
        }else{
            $force_password_change = '0';
        }
        $auditLogDes = "";
        $inactivity_session_time = CoreSetting::where('name','inactivity_session_time')->first();

        if($inactivity_session_time->value != $request->inactivity_session_time){
            $auditLogDes =  "Inactivity session time has been amended";
        }

        $force_password_change_val = CoreSetting::where('name','force_password_change')->first();

        if($force_password_change_val->value != $force_password_change){
             $auditLogDes =  "Force password change has been amended";
        }

        $login_attempt = CoreSetting::where('name','login_attempt')->first();
        if($login_attempt->value != $request->login_attempt){
             $auditLogDes =  "Login attempt has been amended";
        }

        $inactive_user_suspension = CoreSetting::where('name','inactive_user_suspension')->first();
        if($inactive_user_suspension->value != $request->inactive_user_suspension){
             $auditLogDes =  "Inactive user suspension has been amended";
        }

        $delete_suspended_user = CoreSetting::where('name','delete_suspended_user')->first();
        // $delete_suspended_user ."!=" .$request->delete_suspended_user;
        if($delete_suspended_user->value != $request->delete_suspended_user){
            $auditLogDes =  "Delete suspended user has been amended";
        }

        if($auditLogDes == ""){
            $auditLogDes = 'Session has updated session detail.';
        }

        $data['inactivity_session_time']=CoreSetting::where('name','inactivity_session_time')->update(['value'=>$request->inactivity_session_time]);
        $data['force_password_change']=CoreSetting::where('name','force_password_change')->update(['value'=>$force_password_change]);
        $data['login_attempt']=CoreSetting::where('name','login_attempt')->update(['value'=>$request->login_attempt]);
        $data['inactive_user_suspension']=CoreSetting::where('name','inactive_user_suspension')->update(['value'=>$request->inactive_user_suspension]);
        $data['delete_suspended_user']=CoreSetting::where('name','delete_suspended_user')->update(['value'=>$request->delete_suspended_user]);
        $data['contact_forward_email']=CoreSetting::where('name','contact_forward_email')->update(['value'=>$request->contact_forward_email]);

        $moduleName = 'Core Setting';
        $moduleActivity = 'Updated Coresetting';
        // $description = getUserRoles(Auth::user()->roles->first()->name).' has updated session detail.';
        $description = $auditLogDes;
        $usercompanyName = getUserCompanyName(Auth::user());

        /*Start - Add action in audit log*/
        captureAuditLog($moduleName,$moduleActivity,$description,$request->all(),$usercompanyName);
        /*End - Add action in audit log*/

        $notification = array(
            'message' => 'Coresetting has been successfully updated',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
