<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use DateTime;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDynamicEmail;
use App\CoreSetting;
use App\DistributorUser;
use App\TreatmentCenterUser;


class UserController extends Controller
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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('ema_users');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $getAllRoles = Role::all();
        $getAllPermission = Permission::all();
        return view('users.add-user',compact('getAllRoles','getAllPermission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id',$id)->with('roles')->first();
        if($user){
            return response()->json(['success'=>true,'data'=>$user,'message'=>'User found.']);
        }
        else{
            return response()->json(['success'=>false,'message'=>'User does not exist.']);
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
        $user = User::where('id',$id)->with('roles')->first();
        if($user){
            return response()->json(['success'=>true,'data'=>$user,'message'=>'User found.']);
        }
        else{
            return response()->json(['success'=>false,'message'=>'User does not exist.']);
        }
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
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|unique:users,email,NULL,id,deleted_at,NULL.$id',

        ]);
        if ($validator->fails()) {
            if($request->ajax() || $request->wantsJson()){
                return response()->json(['success' => false,'errors'=>$validator->errors()], 422);
            }
            else{
                return redirect()->back()->withErrors($validator);
            }
        }
        if (User::where('id', $id)->exists()) {

            $user = User::findOrFail($id);
            $is_email_change = 0;
            if($user->email != $request->input('email') ){
                $data = array();
                $data['slug'] = 'email_change_on_profile_update';
                $data['name'] = $request->input('edit_name');
                $data['email'] = $request->input('email');
                $data['old_email'] = $user->email;
                $data['primary_telephone_number'] = $request->input('edit_telno');
                $data['mobile_telephone_number'] = $request->input('edit_mobno');
                Mail::to($user->email)->queue(new SendDynamicEmail($data));
                $is_email_change = 1;
            }
            /* Get original Data  before update*/
                $originalData = getOriginalData($user);

            /* Get original Data  before update*/

            $user->update([
                'name' => $request->input('edit_name'),
                'email' => $request->input('email'),
                'primary_telephone_number' =>  addSymbol($request->input('edit_telno')),
                'mobile_telephone_number' =>  addSymbol($request->input('edit_mobno')),
            ]);
            $user->syncRoles($request->input('edit_role'));

            $moduleName = 'user';
            $moduleActivity = 'Updated user';
            $companyName = EMA_COMPANY_NAME;
            // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has updated '.ucfirst($originalData['name'])." (".getUserRoles($request->input('edit_role')).").";
            $description=ucfirst($originalData['name'])." (".getUserRoles($request->input('edit_role')).") has been amended for ".$companyName;
            $requestData = $request->all();

            /*Add action in audit log*/
            captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$companyName);
            /*Add action in audit log*/

            $data['slug'] = 'user_update';
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['username'] = $user->username;
            $data['primary_telephone_number'] = $user->primary_telephone_number;
            $data['mobile_telephone_number'] = $user->mobile_telephone_number;
            Mail::to($user->email)->queue(new SendDynamicEmail($data));

            $moduleName = 'Email';
            $moduleActivity = 'Email logged for Updated user';
            $companyName = EMA_COMPANY_NAME;
            // $description = 'Email has been sent to updated user: '.$user->name.".";
            $description="An update user email has been sent to ".ucfirst($user->name);
            $requestData = array('user_id'=>$id);

            /*Add action in audit log*/
            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
            /*Add action in audit log*/

            if($is_email_change == 1){
                /* AT-2049 - Send SMS */
                $template_slug = 'email_update';
                $recipient = $user->mobile_telephone_number;
                SendTwilioSMS($recipient, $template_slug);
            }else{
                /* AT-2049 - Send SMS */
                $template_slug = 'user_details_update';
                $recipient = $user->mobile_telephone_number;
                SendTwilioSMS($recipient, $template_slug);
            }


            if($request->ajax() || $request->wantsJson()){
                return response()->json(['success'=>true,'message'=>'User record updated successfully'], $this->successStatus);
            }else{
               return redirect()->route('home')
                            ->with('success','User updated successfully.');
            }
        }
        else{
            return response()->json([
                "status"=>false,
                "error" => "Resource not found",
                "message" => "User not found"
              ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $authenticated_user = Auth::user();
        if($authenticated_user->hasRole(['system administrator','treatment centre manager','distributor principal'])){
            if(User::where('id', $id)->exists()) {
                $user = User::find($id);

                $userStatus = User::find($id);
                $userStatus->status=2;
                $userStatus->save();

                $getRoleName = $user->getRoleNames()[0];
                $module = $request->input('module');

                /*Add action in audit log*/

                /* Get companyName of logged in user */
                    $companyName = getUserCompanyName(Auth::user());
                /* Get companyName of logged in user */

                /* Get Company name of on which user activity was performed */
                    $getUserCompanyname = getUserCompanyName($user);
                    $companyType = getUserCompanyType($user);
                /* Get Company name of on which user activity was performed */

                $moduleName = 'user';
                $moduleActivity = 'Deleted user';
                // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has deleted '.ucfirst($user->name)." (".getUserRoles($getRoleName).").";
                if($companyType==EMA_COMPANY_NAME){
                    $description = ucfirst($user->name)." (".getUserRoles($getRoleName).") for ".$getUserCompanyname." has been deleted";
                }else{
                    $description = ucfirst($user->name)." (".getUserRoles($getRoleName).") for ".$companyType." ".$getUserCompanyname." has been deleted";
                }

                $requestData = array('user_id'=>$id);

                    captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
                /*Add action in audit log*/

                $data['slug'] = 'delete_user';
                $data['name'] = $user->name;
                Mail::to($user->email)->queue(new SendDynamicEmail($data));

                /*Add action in audit log*/
                $moduleName = 'Email';
                $moduleActivity = 'Email logged for Deleted user';
                // $description = 'Email has been sent to deleted user: '.ucfirst($user->name).".";
                $description = "A delete user email has been sent to ".ucfirst($user->name);
                $requestData = array('user_id'=>$id);

                /*Add action in audit log*/
                captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
                /*Add action in audit log*/

                /* AT-2049 - Send SMS */
                $template_slug = 'account_delete';
                $recipient = $user->mobile_telephone_number;
                SendTwilioSMS($recipient, $template_slug);

                // $user->delete();

                return response()->json([
                    "success"=>true,
                    "message" => "User successfully deleted"
                ], 202);
            } else {
                return response()->json([
                    "success"=>false,
                    "message" => "User not found"
                ], 404);
            }
        }
        else{
            return response()->json([
                "success"=>false,
                "message" => "Unauthenticated user"
                ], 422);
        }
    }

    public function login(){
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('authToken')->accessToken;
            $success['user_id'] =  $user->id;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required|string|unique:users,email,NULL,id,deleted_at,NULL',
            'username' => 'required|string|unique:users,username,NULL,id,deleted_at,NULL',
            'role' => 'required',
            'primary_telephone_number'=>['required'],
            'mobile_telephone_number'=>['required']
        ]);

        if ($validator->fails()) {
            if($request->ajax() || $request->wantsJson()){
                return response()->json(['success' => false,'errors'=>$validator->errors()], 422);
            }
            else{
                return redirect()->back()->withErrors($validator);
            }
        }

        /*Generate the internal-id */
        $getData = getTotalRecords('users');
        $getgeneratedInternalId  = generateInternalId($getData['incremented_record']);
        /*Generate the internal-id */

        $input = $request->all();
        $password = Str::random(10);
        $input['password'] = bcrypt($password);
        $input['internal_id'] = $getgeneratedInternalId;
        $input['primary_telephone_number'] =  addSymbol($input['primary_telephone_number']);
        $input['mobile_telephone_number'] = addSymbol($input['mobile_telephone_number']);

        $user = User::create($input);
        $user->assignRole($request->input('role'));
        $user['password'] = $password;
        $accessToken =  $user->createToken('authToken')->accessToken;
        $username =  $user->username;


        $moduleName = 'user';
        $moduleActivity = 'Added user';
        $companyName = EMA_COMPANY_NAME;
        // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).' ) has added '.ucfirst($request->input('name')." (".getUserRoles($request->input('role')).").");
        $description = "New user ".ucfirst($request->input('name'))." (".getUserRoles($request->input('role')).") for ".$companyName." has been added ";
        $requestData = $request->all();

        /*Add action in audit log*/
        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
        /*Add action in audit log*/


        $data['slug'] = 'welcome_email';
        $data['name'] = $user->name;
        $data['username'] = $user->username;
        $data['new_password'] =  $password;

        Mail::to($user->email)->queue(new SendDynamicEmail($data));

        $moduleName = 'Email';
        $moduleActivity='Email logged for User added';
        $description = 'Email has been sent to added user : '.ucfirst($user->name);
        $requestData = $request->all();

        /*Add action in audit log*/
        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
        /*Add action in audit log*/


        // return response
        $response = [
            'success' => true,
            'message' => 'User created successfully',
            'accessToken' => $accessToken,
            'username' => $username
        ];

        if($request->ajax() || $request->wantsJson()){
            return response()->json($response, 201);
        }else{
           return redirect()->route('home')
                        ->with('success','User created successfully');
        }

    }

    public function logout(){
        auth()->user()->tokens->each(function($token, $key){
            $token->delete();
        });

        $response = [
            'success' => true,
            'message' => 'Logged out successfully',
        ];

        return response()->json($response,$this->successStatus);
    }

    public function details()
    {
        return response()->json(['success' => 'Succesfully called api'], $this->successStatus);
    }


    public function addAuditLog(Request $request){
        $moduleName = 'thingstream';
        $moduleActivity = 'Thingstream action';
        $description = "thingstream description";
        $requestData = $request->all();

        /*Add action in audit log*/
         $data =  captureAuditLog($moduleName,$moduleActivity,$description,$requestData);
         echo json_encode($data);
        /*Add action in audit log*/
    }

    public function softDeleted()
    {
        $users = User::onlyTrashed()->get();

        $response = $this->successfulMessage(200, 'Successfully', true, $users->count(), $users);
        return response($response);
    }

    private function successfulMessage($code, $message, $status, $count, $payload)
    {

        return [
            'code' => $code,
            'message' => $message,
            'success' => $status,
            'count' => $count,
            'data' => $payload,
        ];

    }

    public function forceLogoutUser(Request $request,$id)
    {
        $authenticated_user = Auth::user();
            if(User::where('id', $id)->exists()) {
                $usercount = \DB::table('sessions')->select('user_id')->where('user_id',$id)->count();
                if($usercount >= 1){
                    $user_to_logout = \App\User::where('id', $id)->first();
                    $getRoleName = $user_to_logout->getRoleNames()[0];
                    $user_to_logout->update(['is_logged_in' => 0]);


                    /*Add action in audit log*/

                    /* Get companyName of logged in user */
                        $companyName = getUserCompanyName(Auth::user());
                    /* Get companyName of logged in user */

                    /* Get Company name of on which user activity was performed */
                        $getUserCompanyname = getUserCompanyName($user_to_logout);
                        $companyType = getUserCompanyType($user_to_logout);
                    /* Get Company name of on which user activity was performed */

                    $moduleName = 'user';
                    $moduleActivity = 'forcefully logout';
                    // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has been force logged out '.ucfirst($user_to_logout->name)." (".getUserRoles($getRoleName).").";
                    if($companyType==EMA_COMPANY_NAME){
                        $description = "User ".ucfirst($user_to_logout->name)." (".getUserRoles($getRoleName).") for ".$getUserCompanyname." has been forcefully logged out";
                    }else{
                        $description = "User ".ucfirst($user_to_logout->name)." (".getUserRoles($getRoleName).") for ".$companyType." ".$getUserCompanyname." has been forcefully logged out";
                    }
                    $requestData = array('user_id'=>$id);

                    captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
                    /*Add action in audit log*/

                    $data['slug'] = 'force_logout';
                    $data['name'] = $user_to_logout->name;
                    Mail::to($user_to_logout->email)->queue(new SendDynamicEmail($data));

                    $moduleName = 'Email';
                    $moduleActivity = 'Email logged for forcefully logout user';
                    $description = 'Email has been sent to forcefully logout user : '.ucfirst($user_to_logout->name);
                    $requestData = array('user_id'=>$user_to_logout->id);

                    /*Add action in audit log*/
                        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
                    /*Add action in audit log*/

                    return response()->json([
                        "status" => true,
                        "message" => "User has been forced logged out successfully"
                    ], 200);
                }else{
                    return response()->json([
                        "status" => true,
                        "message" => "User already has been logged out"
                    ], 200);
                }
            }
            else{
                return response()->json([
                    "status" => false,
                    "message" => "User does not exist."
                ], 404);
            }
    }

    public function getUserList(Request $request)
    {
        if($request->ajax()){
            $searchData =$request->get('search_data');
            $users = User::whereHas("roles", function($q){
                if(Auth::user()->hasRole(['system administrator'])){
                    $q->whereIn("name", ['system administrator','ema analyst','ema service support']);
                }else{
                    $q->whereIn("name", ['ema analyst','ema service support','system administrator']);
                }
                $q->where('name', 'like', '%'.request()->columns[1]['search']['value'].'%');
            })
            ->where('users.id','!=',Auth::user()->id)
            ->where('users.status','!=',2);

            if(isset($searchData) && !empty($searchData)){
                    foreach($searchData as $key=>$val){
                        if($key=="name"){
                            if(!is_null($val)){
                                $users->where($key,'like',"%".$val."%");
                            }
                        }
                        elseif($key=="role"){
                            if(!is_null($val)){
                                $users->whereHas("roles", function($q) use ($val){
                                    $q->where('name', 'like', '%'.$val.'%');
                                });
                            }
                        }
                        else{
                            if(!is_null($val)){
                                $users->where($key,'like',"%".$val."%");
                            }
                        }
                    }

                $users = $users->select('users.id','users.name','users.status','users.email','users.created_at','users.primary_telephone_number','users.mobile_telephone_number','users.is_logged_in')->orderby('users.id','desc')->get();
                $newData = [];
                foreach($users as $key=>$val){
                    $newData[$key][0]=$val->name;
                    $newData[$key][1]= getUserRoles(data_get($val, 'roles.0.name'));
                    $newData[$key][2]= getUserOnline($val->is_logged_in);
                    $newData[$key][3]= getUserStatus($val->status);
                    $newData[$key][4]=$val->email;
                    $newData[$key][5]=$val->primary_telephone_number;
                    $newData[$key][6]=$val->mobile_telephone_number;
                }

                $data = makeNumericArray($newData);
                return $data;
            }else{
                return Datatables::of($users)
                ->addColumn('role',function($user){
                    return $user->roles()->first()->name;
                })
                ->addColumn('status',function($user){
                    if($user->status==0){
                        return 'In Active';
                    }elseif($user->status==1){
                        return 'Active';
                    }elseif($user->status==2){
                        return 'Deleted';
                    }elseif($user->status==3){
                        return 'Suspended';
                    }
                })
                ->addColumn('online',function($user){
                    $green= asset("images/green_dot.png");
                    $red= asset("images/red_dot.png");
                    return $user->is_logged_in != 0 ? '<img src='.$green.' border="0" width="15px" style="text-align: center;" class="img-rounded" align="center" />' : '<img src='.$red.' border="0" width="15px" style="text-align: center;" class="img-rounded" align="center" />';
                })

                ->addColumn('action', function ($user) {
                    $forcelogout = $suspend = $edit = $view = $changePassword = $delete = '';
                    $authUser = Auth::user();
                    if($authUser->hasRole(['system administrator'])){
                         $edit = '<a href="javascript:;" class="editCustomer" title="Edit User" data-toggle="modal" id="'.$user->id.'" data-target="#edit_user"><i class="far fa-edit"></i></a>';
                         $delete = '<a href="javascript:;" id="'.$user->id.'" data-UserName="'.ucfirst($user->name).'" title="Delete User" class="delete-user"><i class="far fa-trash-alt"></i></a>';
                    }
                    if($authUser->hasRole(['system administrator'])){
                        $forcelogout = $user->is_logged_in != 0 ? '<a href="javascript:;" id="'.$user->id.'" data-UserName="'.ucfirst($user->name).'" title="Force Logout User" class="forcelogout"><i class="fas fa-sign-out-alt"></i></a>' : '';
                        $suspend = $user->status != 3 ? '<a href="javascript:;" id="'.$user->id.'" title="Suspend User" class="suspenduser" data-UserName="'.ucfirst($user->name).'"><i class="fas fa-user-lock"></i></a>' : '<a href="javascript:;" id="'.$user->id.'" title="Release User" data-UserName="'.ucfirst($user->name).'" class="releaseuser" ><i class="fas fa-unlock"></i></a>';
                        $changePassword = '<a href="javascript:;" id="'.$user->id.'" title="Reset Password User" data-UserName="'.ucfirst($user->name).'" class="change-password"><i class="fas fa-key"></i></a>';
                    }

                    if($authUser->hasRole(['ema service support'])){
                        if($user->roles()->first()->name!="system administrator"){
                            $forcelogout = $user->is_logged_in != 0 ? '<a href="javascript:;" id="'.$user->id.'" data-UserName="'.ucfirst($user->name).'" title="Force Logout User" class="forcelogout"><i class="fas fa-sign-out-alt"></i></a>' : '';
                            $suspend = $user->status != 3 ? '<a href="javascript:;" id="'.$user->id.'" title="Suspend User" class="suspenduser" data-UserName="'.ucfirst($user->name).'"><i class="fas fa-user-lock"></i></a>' : '<a href="javascript:;" id="'.$user->id.'" title="Release User" data-UserName="'.ucfirst($user->name).'" class="releaseuser" ><i class="fas fa-unlock"></i></a>';
                            $changePassword = '<a href="javascript:;" id="'.$user->id.'" title="Reset Password User" data-UserName="'.ucfirst($user->name).'" class="change-password"><i class="fas fa-key"></i></a>';
                        }
                    }
                    if($authUser->hasRole(['system administrator','ema analyst','ema service support'])){
                        $view = '<a href="javascript:;" class="viewDetails" title="View User" data-UserId='.$user->id.' ><i class="far fa-eye"></i></a> ';
                    }

                    return $edit.$view.$suspend.$changePassword.$forcelogout.$delete;

                })->rawColumns(['online','action','role'])->toJson();
            }
        }
    }

    public function suspendUser(Request $request, $id)
    {
        $authenticated_user = Auth::user();
            $user = User::find($id);
            $getRoleName = $user->getRoleNames()[0];
            if($user->exists()) {
                $user_to_logout = \App\User::where('id', $id)->update(['status' => 3,'is_logged_in'=>0]);

                if(true){
                    $module = $request->input('module');
                    /*Add action in audit log*/

                    /* Get companyName of logged in user */
                        $companyName = getUserCompanyName(Auth::user());
                    /* Get companyName of logged in user */

                    /* Get Company name of on which user activity was performed */
                        $getUserCompanyname = getUserCompanyName($user);
                        $companyType = getUserCompanyType($user);
                    /* Get Company name of on which user activity was performed */

                    $moduleName = 'user';
                    $moduleActivity = 'Suspended user';
                    // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has suspended '.ucfirst($user->name)." (".getUserRoles($getRoleName).").";
                    if($companyType==EMA_COMPANY_NAME){
                        $description = "User ".ucfirst($user->name)." (".getUserRoles($getRoleName).") for ".$getUserCompanyname." has been suspended";
                    }else{
                        $description = "User ".ucfirst($user->name)." (".getUserRoles($getRoleName).") for ".$companyType." ".$getUserCompanyname." has been suspended";
                    }
                    $requestData = array('user_id'=>$id);

                        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
                    /*Add action in audit log*/

                    $data['slug'] = 'suspend_user_account';
                    $data['name'] = $user->name;
                    Mail::to($user->email)->queue(new SendDynamicEmail($data));

                    $moduleName = 'Email';
                    $moduleActivity = 'Email logged for suspended user';
                    $description = 'A suspend user email has been sent to '.ucfirst($user->name);
                    $requestData = array('user_id'=>$id);

                    /*Add action in audit log*/
                        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
                    /*Add action in audit log*/
                }
                return response()->json([
                    "status" => true,
                    "message" => "User Suspended Successfully"
                ], 200);
            }
            else{
                return response()->json([
                    "status" => false,
                    "message" => "User does not exist"
                ], 404);
            }
    }

    public function releaseUser(Request $request, $id)
    {
        $authenticated_user = Auth::user();
            $user = User::find($id);
            $getRoleName = $user->getRoleNames()[0];
            if($user->exists()) {
                $user_to_logout = \App\User::where('id', $id)->update(['status' => 1]);

                if(true){

                    /*Add action in audit log*/

                    /* Get companyName of logged in user */
                        $companyName = getUserCompanyName(Auth::user());
                    /* Get companyName of logged in user */

                    /* Get Company name of on which user activity was performed */
                        $getUserCompanyname = getUserCompanyName($user);
                        $companyType = getUserCompanyType($user);
                    /* Get Company name of on which user activity was performed */

                    $moduleName = 'user';
                    $moduleActivity = 'Released user';
                    // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has released '.ucfirst($user->name)." (".getUserRoles($getRoleName).").";
                    if($companyType==EMA_COMPANY_NAME){
                        $description = "User ".ucfirst($user->name)." (".getUserRoles($getRoleName).") for ".$getUserCompanyname." has been released";
                    }else{
                        $description = "User ".ucfirst($user->name)." (".getUserRoles($getRoleName).") for ".$companyType." ".$getUserCompanyname." has been released";
                    }
                    $requestData = array('user_id'=>$id);

                        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
                    /*Add action in audit log*/

                    $data['slug'] = 'release_user_account';
                    $data['name'] = $user->name;
                    Mail::to($user->email)->queue(new SendDynamicEmail($data));

                    $moduleName = 'Email';
                    $moduleActivity = 'Email logged for Released user';
                    $description = 'A release user email has been sent to '.ucfirst($user->name);
                    $requestData = array('user_id'=>$id);

                    /*Add action in audit log*/
                        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
                    /*Add action in audit log*/
                }
                return response()->json([
                    "status" => true,
                    "message" => "User Released Successfully"
                ], 200);
            }
            else{
                return response()->json([
                    "status" => false,
                    "message" => "User does not exist."
                ], 404);
            }
    }

    /**
    * check value is exist or not
    * @return true/false
	*/
	public function checkUsernameIsExists(Request $request){
		$field =    $request->input('field');
		$value =    $request->input('value');

		$getCount = User::where($field,$value)
                    ->where('status',1)
                    ->whereNull('deleted_at');

		$getCount  = $getCount->count();

		return $getCount;

	}

    /**
    * UpdatePassword
    * @return true/false
    */
    public function updatePassword(Request $request){
        $this->validate($request, [
            'new_password'     => 'required|min:6',
            'password_confirmation' => 'required|same:new_password',
        ]);
        $user = User::find(auth()->user()->id);
        if(Hash::check($request->new_password, $user->password)){
            return back()
                ->withInput($request->only('new_password'))
                ->withErrors(['new_password' => 'The specified password does not match the database password.']);
        }else{

            /* Get original Data  before update*/
               $originalData = getOriginalData($user);
            /* Get original Data  before update*/

            if(!empty($originalData)){
                $originalData['created_at'] = date('d-m-Y H:i:s',strtotime($originalData['created_at']));
                $originalData['updated_at'] = date('d-m-Y H:i:s',strtotime($originalData['updated_at']));
                $originalData['last_login_activity'] = date('d-m-Y H:i:s',strtotime($originalData['last_login_activity']));
            }

            $user = Auth::user();
            $user->password = bcrypt($request->new_password);
            $user->first_time_login = 1;
            $user->is_logged_in = 0;
            $user->save();

             /* Get role Id by User Id */
             $roleId = getroleIdByUserId($user->id);
             /* Get role Id by User Id */

            $usercompanyName = getUserCompanyName($user);
            $getRoleName = $user->getRoleNames()[0];
            $moduleName = 'Change Password';
            $moduleActivity = 'New password updated';
            // $description = ucfirst($user->name)." (".getUserRoles($getRoleName).') has updated password.';
            $description = "Password has been updated";

            /*Start - Add action in audit log*/
            captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$usercompanyName);
            /*End - Add action in audit log*/

            $data_new = array();
            $users=$user->toArray();
            $data_new['slug'] = 'change_password';
            $data_new['name'] = $users['name'];
            $data_new['email_address'] = $users['email'];
            $data_new['username'] = $users['username'];
            $data_new['primary_telephone_number'] = $users['primary_telephone_number'];
            $data_new['mobile_telephone_number'] = $users['mobile_telephone_number'];
            Mail::to($users['email'])->queue(new SendDynamicEmail($data_new));

            $moduleName = 'Email';
            $moduleActivity = 'Email logged for update password';
            $description = 'Email has been sent to user for password update: '.ucfirst($user->name);
            $requestData = array('user_id'=>$user->id);

            /*Start - Add action in audit log*/
            captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$usercompanyName);
            /*End - Add action in audit log*/

            $notification = array(
            'message' => 'Password successfully updated',
            'alert-type' => 'success'
            );

            return redirect()->route('login')->withCookie(cookie('passwordUpdate', '1',45000));
        }

    }

    public function resetPasswordUser(Request $request, $id)
    {
        $authenticated_user = Auth::user();
            $user = User::find($id);
            $getRoleName = $user->getRoleNames()[0];
            if($user->exists()) {

                if($user->status == 3){
                    return response()->json([
                        "status" => false,
                        "message" => "Reset password does not functioning for suspended user."
                    ], 200);
                }

                $users = User::find($user->id);

                /* Get original Data  before update*/
                     $originalData = getOriginalData($users);
                /* Get original Data  before update*/

                if(!empty($originalData)){
                    $originalData['created_at'] = date('d-m-Y H:i:s',strtotime($originalData['created_at']));
                    $originalData['updated_at'] = date('d-m-Y H:i:s',strtotime($originalData['updated_at']));
                    $originalData['last_login_activity'] = date('d-m-Y H:i:s',strtotime($originalData['last_login_activity']));
                }

                $password = Str::random(10);
                $new_password = bcrypt($password);
                $users->password = $new_password;
                $users->first_time_login = 0;
                $users->is_logged_in = 0;
                $users->save();

                /*Add action in audit log*/
                // $usercompanyName = getUserCompanyName($authenticated_user);
                // $companyType = getUserCompanyType(Auth::user());

                /* Get companyName of logged in user */
                    $companyName = getUserCompanyName(Auth::user());
                /* Get companyName of logged in user */

                /* Get Company name of on which user activity was performed */
                    $getUserCompanyname = getUserCompanyName($users);
                    $companyType = getUserCompanyType($users);
                /* Get Company name of on which user activity was performed */

                $moduleName = 'Reset Password';
                $moduleActivity = 'Force change password - Request for new password';
                // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has requested for new password of user '.ucfirst($user->name)." (".getUserRoles($getRoleName).").";
                if($companyType==EMA_COMPANY_NAME){
                    $description = "Force Change password request is sent to user ".ucfirst($user->name)." (".getUserRoles($getRoleName).") for ".$getUserCompanyname;
                }else{
                    $description = "Force Change password request is sent to user ".ucfirst($user->name)." (".getUserRoles($getRoleName).") for ".$companyType." ".$getUserCompanyname;
                }

                /*Start - Add action in audit log*/
                captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$companyName);

                $data['slug'] = 'reset_password';
                $data['name'] = $users->name;
                $data['new_password'] =  $password;

                Mail::to($user->email)->queue(new SendDynamicEmail($data));

                $moduleName = 'Email';
                $moduleActivity = 'Email logged for reset password';
                $description = 'Email has been sent to user for reset password: '.ucfirst($user->name);
                $requestData = array('user_id'=>$user->id);

                /*Add action in audit log*/
                    captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$companyName);
                /*Add action in audit log*/

                /* AT-2049 - Send SMS */
                $template_slug = 'reset_password';
                $recipient = $users->mobile_telephone_number;
                SendTwilioSMS($recipient, $template_slug);

                return response()->json([
                    "status" => true,
                    "message" => "New password has been sent on user's email address"
                ], 200);
            }
            else{
                return response()->json([
                    "status" => false,
                    "message" => "User does not exist"
                ], 404);
            }
    }

    public function changePasswordView()
    {
        $setting = CoreSetting::where('name','force_password_change')->first();
        if(Auth::user()->first_time_login == 0 && $setting->value == 1){
            return view('users.reset-password');
        }else{
            return redirect()->route('home');
        }

    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password_change' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'new_confirm_password' => ['required', 'same:new_password_change'],
        ]);

        $user = Auth::user();

        /* Get original Data  before update*/
            $originalData = getOriginalData($user);
        /* Get original Data  before update*/

        if(!empty($originalData)){
            $originalData['created_at'] = date('d-m-Y H:i:s',strtotime($originalData['created_at']));
            $originalData['updated_at'] = date('d-m-Y H:i:s',strtotime($originalData['updated_at']));
            $originalData['last_login_activity'] = date('d-m-Y H:i:s',strtotime($originalData['last_login_activity']));
        }
        $user->password = Hash::make($request->new_password_change);
        $user->first_time_login = 1;
        $user->save();

        /* Get role Id by User Id */
            $roleId = getroleIdByUserId($user->id);
        /* Get role Id by User Id */

        $allDistributorIds = array('7','8','9');
        if(in_array($roleId[0],$allDistributorIds)){
            $getDistributorCompanyData = getDistributorCompanyDataById($user->id);
            $companyName = ucfirst($getDistributorCompanyData->full_company_name);
        }elseif($roleId[0]==5){
            $getTreatmentCentreData = getGetTreatmentCentreCompanyDataByUserId($user->id);
            $companyName = ucfirst($getTreatmentCentreData->full_company_name);
        }else{
            $companyName = EMA_COMPANY_NAME;
        }

        $moduleName = 'Change Password';
        $moduleActivity = 'New password updated';
        // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has updated password.';
        $description = "Password has been updated";

        /*Start - Add action in audit log*/
        captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$companyName);
        /*End - Add action in audit log*/

        $data_new = array();
        $users=$user->toArray();
        $data_new['slug'] = 'change_password';
        $data_new['name'] = $users['name'];
        $data_new['email_address'] = $users['email'];
        $data_new['username'] = $users['username'];
        $data_new['primary_telephone_number'] = $users['primary_telephone_number'];
        $data_new['mobile_telephone_number'] = $users['mobile_telephone_number'];
        Mail::to($users['email'])->queue(new SendDynamicEmail($data_new));

        $moduleName = 'Email';
        $moduleActivity = 'Email logged for change password';
        $description = 'Email has been sent to user for change update: '.ucfirst($user->name);
        $requestData = array('user_id'=>$user->id);

        /*Start - Add action in audit log*/
        captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$companyName);
        /*End - Add action in audit log*/

        $notification = array(
            'logout_message' => 'Password successfully updated',
            'logout-alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function checkExistingPassword(Request $request){
        $user = User::find(auth()->user()->id);

        if(Hash::check($request->current_password, $user->password)){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }
    public function editProfile(Request $request){
        $validator=Validator::make($request->all(),[
            'edit_profile_name'=>['required'],
            'edit_profile_primary_telephone_number'=>['required'],
            'edit_profile_mobile_telephone_number'=>['required']
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        $user=User::find(Auth::user()->id);

        /* Get original Data  before update*/
        $originalData = getOriginalData($user);
        /* Get original Data  before update*/

        $oldUser=$user->toArray();
        $is_email_change = 0;
        $companyName = getUserCompanyName(Auth::user());
        if($oldUser['email'] != $request->edit_profile_email ){
            $data = array();
            $data['slug'] = 'email_change_on_profile_update';
            $data['name'] = $request->edit_profile_name;
            $data['email'] = $request->edit_profile_email;
            $data['old_email'] = $oldUser['email'];
            $data['username'] = $oldUser['username'];
            $data['primary_telephone_number'] = addSymbol($request->edit_profile_primary_telephone_number);
            $data['mobile_telephone_number'] = addSymbol($request->edit_profile_mobile_telephone_number);
            Mail::to($user->email)->queue(new SendDynamicEmail($data));
            $is_email_change = 1;

            $moduleName="Email";
            $moduleActivity="Email Logged user has change email address";
            $description = 'Email has been sent to user for change email address: '.ucfirst($user->name);
            captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$companyName);
        }

        if($is_email_change == 1){
            /* AT-2049 - Send SMS */
            $template_slug = 'email_update';
            $recipient = $user->mobile_telephone_number;
            SendTwilioSMS($recipient, $template_slug);
        }else{
            /* AT-2049 - Send SMS */
            $template_slug = 'user_details_update';
            $recipient = $user->mobile_telephone_number;
            SendTwilioSMS($recipient, $template_slug);
        }

        $user->name=$request->edit_profile_name;
        $user->primary_telephone_number=$request->edit_profile_primary_telephone_number;
        $user->email=$request->edit_profile_email;
        $user->mobile_telephone_number=$request->edit_profile_mobile_telephone_number;
        $user->save();


        $getRoleName = $user->getRoleNames()[0];

        $moduleName="Update User Detail";
        $moduleActivity="Update User Detail";
        // $description= Auth::user()->name." (".getUserRoles(Auth::user()->roles->first()->name).') has edited his detail.';
        $description = "User details updated";
        captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$companyName);

        $data_new = array();
        $users=$user->toArray();
        $data_new['slug'] = 'user_update';
        $data_new['name'] = $users['name'];
        $data_new['email'] = $users['email'];
        $data_new['username'] = $users['username'];
        $data_new['primary_telephone_number'] = $users['primary_telephone_number'];
        $data_new['mobile_telephone_number'] = $users['mobile_telephone_number'];
        Mail::to($users['email'])->queue(new SendDynamicEmail($data_new));

        $moduleName="Email";
        $moduleActivity="Email Logged profile update";
        $description = 'Email has been sent to user for profile update: '.ucfirst($user->name);
        captureAuditLog($moduleName,$moduleActivity,$description,$originalData,$companyName);

        $notification = array(
            'message' => 'User profile successfully updated',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }

    public function checkCurrentPassword(Request $request){
        $user = User::find(auth()->user()->id);

        if(Hash::check($request->new_password, $user->password)){
            return response()->json(false);
        }else{
            return response()->json(true);
        }
    }
    public function uniqueuseremail(Request $request){
        $user=User::where('email',$request->edit_profile_email)
                    ->where('id','<>',Auth::id())
                    ->where('status','!=',2)
                    ->first();
        if($user){
            return response()->json(false);
        }
        else{
            return response()->json(true);
        }
    }
}
