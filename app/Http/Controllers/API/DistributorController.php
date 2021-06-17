<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Distributor;
use Auth;
use Yajra\DataTables\Facades\DataTables;
use Input;
use App\Country;
use App\DistributorUser;
use App\User;
use \Illuminate\Support\Facades\URL;
use DateTime;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDynamicEmail;

class DistributorController extends Controller
{
    public $successStatus = 200;
    /**
    *   Add treatment center process
    *   @return boolean success/error
    */
    public function addDistributor(Request $request){

        $userInput=array();
        $input=$request->all();
        $validator=Validator::make($request->all(),[
            'full_company_name' => ['required','max:255'],
            'abbreviated_company_name' =>['required', 'max:255'],
            'group_name' =>['max:255'],
            'full_address' =>['max:255'],
            'building_name'=>['max:255'],
            'address1'=>['required'],
            'address2'=>['max:255'],
            'address3'=>['max:255'],
            'state'=>['required'],
            'zipcode'=>['required'],
            'position'=>['required'],
            'country_id'=>['required'],
            'name_of_primary_contact' =>['required','string','max:100'],
            'telephone_number_of_primary_contact' =>['required'],
            'mobile_number_of_primary_contact' =>['required'],
            'email_of_primary_contact' =>['required','max:255'],
            'distributor_code' =>['required'],
            ]);

            if($validator->fails()){
                return response()->json(['error'=>$validator->errors()],401);
            }
            else
            {
                /*Add the distributor */
                $getData = getTotalRecords('distributors');
                $getgeneratedInternalId  = generateInternalId($getData['incremented_record']);

                $distributor=new Distributor;
                $distributor->full_company_name=$input['full_company_name'];
                $distributor->internal_id=$getgeneratedInternalId;
                $distributor->abbreviated_company_name=$input['abbreviated_company_name'];
                $distributor->group_name=(isset($input['group_name']) ? $input['group_name'] : '');
                $distributor->full_address=(isset($input['full_address']) ? $input['full_address'] : '');
                $distributor->building_name=(isset($input['building_name']) ? $input['building_name'] : '');
                $distributor->address1=$input['address1'];
                $distributor->address2=(isset($input['address2']) ? $input['address2'] : '');
                $distributor->address3=(isset($input['address3']) ? $input['address3'] : '');
                $distributor->state=$input['state'];
                $distributor->zipcode=$input['zipcode'];
                $distributor->position=$input['position'];
                $distributor->country_id=$input['country_id'];
                $distributor->fax_number=(isset($input['fax_number']) ? addSymbol($input['fax_number']) : '');
                $distributor->web_site=(isset($input['web_site']) ? $input['web_site'] : '');
                $distributor->name_of_primary_contact=$input['name_of_primary_contact'];
                $distributor->telephone_number_of_primary_contact=addSymbol($input['telephone_number_of_primary_contact']);
                $distributor->mobile_number_of_primary_contact=addSymbol($input['mobile_number_of_primary_contact']);
                $distributor->email_of_primary_contact=$input['email_of_primary_contact'];
                $distributor->distributor_code=$input['distributor_code'];
                $distributor->created_by=Auth::user()->id;
                $distributor->ip_address=request()->ip();

                if($distributor->save()){

                    /*Start - Add action in audit log*/
                    $moduleName = 'distributor';
                    $moduleActivity = 'Added Distributor';
                    $companyName = ucfirst($input['full_company_name']);
                    $usercompanyName = getUserCompanyName(Auth::user());
                    // $description = ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has Added '.$companyName." Distributor.";
                    $description = "Distributor ".$companyName." has been added";
                    captureAuditLog($moduleName,$moduleActivity,$description,$input,$usercompanyName);
                    /*End - Add action in audit log*/

                    $verification_url = URL::temporarySignedRoute('verifyEmail', now()->addDay(), ['email' => $input['email_of_primary_contact']]);

                    $data['slug'] = 'distributor_add';
                    $data['name_of_primary_contact'] = ucfirst($input['name_of_primary_contact']);
                    $data['confirmation_link'] = $verification_url;
                    $data['companyName'] = $companyName;
                    Mail::to($input['email_of_primary_contact'])->queue(new SendDynamicEmail($data));

                    $moduleName='Email';
                    $moduleActivity='Email logged for Distributor Added';
                    $description = 'Email has been sent to Distributor primary user: '.ucfirst($data['name_of_primary_contact']);
                    $requestData = array('distributor_id'=>$distributor->id);

                    /*Add action in audit log*/
                     captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                     /*Add action in audit log*/

                    $message = 'Distributor created successfully';
                    $response = [
                        'success' => 'true',
                        'message' => $message,
                        'last_inserted_id'=>$distributor->id,
                    ];
                    return response()->json($response, $this->successStatus);
                }else{
                    $message = 'Something wrong please try again';
                    $response = [
                        'success' => 'fail',
                        'message' => $message,
                    ];
                    return response()->json($response, 500);
                }
            }
        }
        public function getDistributorData(Request $request,$id=null)
        {

            $data['country']=Country::orderBy('name')->get();
            if($request->ajax()){

                /* View the distributor */
                if($request->id){
                    $distributor=Distributor::find($request->id);
                    if($distributor){
                        return response()->json(['data'=> $distributor ,'success' => 1 ]);
                    }else{
                        return response()->json(['message'=>'this id not found in database']);
                    }
                }else{
                    $searchData = $request->search_data;
                    if(!empty($searchData)){
                        $getdistributorArray = Distributor::getDistributorListData($searchData);
                        if(!empty($getdistributorArray)){
                            foreach($getdistributorArray as $key=>$distributorRow){
                                $newdistributorRow['distributor_code'] =$distributorRow['distributor_code'];
                                $newdistributorRow['full_company_name'] =$distributorRow['full_company_name'];
                                $newdistributorRow['primary_region'] = $distributorRow['primary_region'];
                                $newdistributorRow['name_of_primary_contact'] =$distributorRow['name_of_primary_contact'];
                                $newdistributorRow['email_of_primary_contact'] =$distributorRow['email_of_primary_contact'];
                                $newdistributorRow['mobile_number_of_primary_contact'] =$distributorRow['mobile_number_of_primary_contact'];
                                $newdistributorRow['total_treatmentcetre'] = $distributorRow['total_treatmentcetre'];
                                $newdistributorRow['total_install_device'] = $distributorRow['total_install_device'];
                                if($distributorRow['status']==3 ){
                                    $newdistributorRow['status'] = 'Suspended';
                                }else{
                                    $newdistributorRow['status'] = 'Active';
                                }
                                $getdistributorArray[$key]  = $newdistributorRow;
                            }
                        }

                        $data = makeNumericArray($getdistributorArray);
                        return $data;
                    }else{
                        $regionSearch = $request->get('columns')['2']['search']['value'];
                        $totalNumberOfTreatmentCentre = $request->get('columns')['6']['search']['value'];
                        $totalNumberOfInstalledDevice = $request->get('columns')['7']['search']['value'];
                        $distributor = Distributor::getDistributorListData(null);

                        if(!empty($regionSearch)){
                            $distributor->havingRaw('LOWER(primary_region) LIKE ?', ["%{$regionSearch}%"]);
                        }

                        if(!empty($totalNumberOfTreatmentCentre)){
                            $distributor->having('total_treatmentcetre',$totalNumberOfTreatmentCentre);
                        }

                        if(!empty($totalNumberOfInstalledDevice)){
                            $distributor->having('total_install_device',$totalNumberOfInstalledDevice);
                        }
                        return Datatables::of($distributor)
                        ->addColumn('status',function($distributor) {
                            if($distributor->status==1){
                                return 'Active';
                            }elseif($distributor->status==3){
                                return 'Suspended';
                            }

                        })
                        ->addColumn('action', function ($distributor_id) {
                            $suspend = '';
                            $delete='';
                            $edit ='';
                            $view = '';
                            $authUser = Auth::user();

                            $getData = getDistributorAlltreatmentCentreIds($distributor_id->id);
                            $getTotalTreatmentCentre = count($getData);

                            $getUsersData = getDistributorCompanyAllUsersByCompanyId($distributor_id->id);
                            $getTotalCompanyUsers = count($getUsersData);

                            if($authUser->hasRole(['system administrator'])){
                                if($getTotalCompanyUsers==0 && $getTotalTreatmentCentre==0){
                                    $delete='<a href="javascript:;"  class="delete-detail" id="confirmationRevertYes" data-distributorId='.$distributor_id->id.' data-distributordeleteName="'.ucfirst($distributor_id->full_company_name).'"><i class="far fa-trash-alt" title="Delete Distributor" data-toggle="tooltip"></i></a>';
                                }
                                $edit = '<a href="javascript:;" class="editDetail" data-distributorId='.$distributor_id->id.' ><i class="far fa-edit" title="Edit Distributor" data-toggle="tooltip"></i></a>';
                                $suspend = $distributor_id->status != 3 ? '<a href="javascript:;" data-distributorId="'.$distributor_id->id.'" data-distributorName="'.ucfirst($distributor_id->full_company_name).'" class="suspenddistributor" title="Suspend Distributor" data-toggle="tooltip">
                                <i class="fas fa-user-lock"></i></a>' : '<a href="javascript:;" data-distributorId="'.$distributor_id->id.'" data-distributorname="'.ucfirst($distributor_id->full_company_name).'"  class="releasedistributor" >
                                <i class="fas fa-unlock" title="Release Distributor" data-toggle="tooltip"></i></a>';
                            }

                            if($authUser->hasRole(['system administrator','ema analyst','ema service support','distributor sales','distributor service','distributor principal'])){
                                $view = '<a href="'.url('distributor-list/'.$distributor_id->internal_id).'" data-distributorId='.$distributor_id->id.' title="View Distributor" data-toggle="tooltip"><i class="far fa-eye"></i></a>';
                            }
                            return $edit.$view.$suspend.$delete;
                        })
                        ->filterColumn('primary_region', function($query, $keyword) {
                            $query->havingRaw('LOWER(primary_region) LIKE ?', ["%{$keyword}%"]);
                        })
                        ->filterColumn('total_treatmentcetre', function($query, $keyword) {
                            $query->havingRaw('LOWER(total_treatmentcetre) LIKE ?', ["%{$keyword}%"]);
                        })
                        ->filterColumn('total_install_device', function($query, $keyword) {
                            $query->havingRaw('LOWER(total_install_device) LIKE ?', ["%{$keyword}%"]);
                        })
                        ->make(true);
                    }
                }
                /* view the distributr */
            }else{
                return view('distributor.distributor-list',$data);
            }
        }
        public function updateDistributor(Request $request){
            $userInput=array();
            $input=$request->all();
            $distributor_id=$input['id'];
            $validator=Validator::make($request->all(),[
                'full_company_name'=>['required','max:255'],
                'abbreviated_company_name' =>['required','max:255'],
                'group_name' =>['max:255'],
                'full_address' =>['max:255'],
                'building_name'=>['max:255'],
                'address1'     =>['required'],
                'address2'     =>['max:255'],
                'address3'     =>['max:255'],
                'state'        =>['required'],
                'zipcode'      =>['required'],
                'position'     =>['required'],
                'country_id'   =>['required'],
                'name_of_primary_contact' =>['required','string','max:100'],
                'telephone_number_of_primary_contact' =>['required'],
                'mobile_number_of_primary_contact' =>['required'],
                'email_of_primary_contact' =>['required','max:255'],
                'distributor_code' =>['required'],
                ]);
                if($validator->fails()){
                    return response()->json(['error'=>$validator->errors()],401);
                }else{
                    $distributor=Distributor::find($distributor_id);
                    $oldEmail = $distributor->email_of_primary_contact;
                    $usercompanyName = getUserCompanyName(Auth::user());

                    /* Get original Data  before update*/
                    $originalData = getOriginalData($distributor);
                    /* Get original Data  before update*/

                    /* update the distributor */
                    $distributor->full_company_name=$input['full_company_name'];
                    $distributor->abbreviated_company_name=$input['abbreviated_company_name'];
                    $distributor->group_name=(isset($input['group_name']) ? $input['group_name'] : '');
                    $distributor->full_address=(isset($input['full_address']) ? $input['full_address'] : '');
                    $distributor->building_name=(isset($input['building_name']) ? $input['building_name'] : '');
                    $distributor->address1=$input['address1'];
                    $distributor->address2=(isset($input['address2']) ? $input['address2'] : '');
                    $distributor->address3=(isset($input['address3']) ? $input['address3'] : '');
                    $distributor->state=$input['state'];
                    $distributor->zipcode=$input['zipcode'];
                    $distributor->position=$input['position'];
                    $distributor->country_id=$input['country_id'];
                    $distributor->fax_number=(isset($input['fax_number']) ? addSymbol($input['fax_number']) : '');
                    $distributor->web_site=(isset($input['web_site']) ? $input['web_site'] : '');
                    $distributor->name_of_primary_contact=$input['name_of_primary_contact'];
                    $distributor->telephone_number_of_primary_contact=addSymbol($input['telephone_number_of_primary_contact']);
                    $distributor->mobile_number_of_primary_contact=addSymbol($input['mobile_number_of_primary_contact']);
                    $distributor->email_of_primary_contact=$input['email_of_primary_contact'];
                    $distributor->distributor_code=$input['distributor_code'];
                    $distributor->created_by=Auth::user()->id;
                    $distributor->ip_address=request()->ip();
                    $distributor->save();

                    if($distributor){
                        $moduleName='distributor';
                        $moduleActivity='Updated Distributor';
                        $companyName = ucfirst($input['full_company_name']);
                        // $moduleDescription=ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has updated ' .$companyName ." Distributor";
                        $moduleDescription= "Distributor ".$companyName." has been updated";
                        captureAuditLog($moduleName,$moduleActivity,$moduleDescription,$originalData,$usercompanyName);

                        $data['slug'] = 'distributor_update';
                        $data['primary_contact_name'] = $input['name_of_primary_contact'];
                        $data['full_company_name']=$input['full_company_name'];
                        $data['abbreviated_company_name']=$input['abbreviated_company_name'];
                        $data['group_name']=(isset($input['group_name']) ? $input['group_name'] : '');
                        $data['full_address']=(isset($input['full_address']) ? $input['full_address'] : '');
                        $data['building_name']=(isset($input['building_name']) ? $input['building_name'] : '');
                        $data['address1']=$input['address1'];
                        $data['address2']=(isset($input['address2']) ? $input['address2'] : '');
                        $data['address3']=(isset($input['address3']) ? $input['address3'] : '');
                        $data['state']=$input['state'];
                        $data['zipcode']=$input['zipcode'];
                        $data['position']=$input['position'];
                        $data['country_id']=$input['country_id'];
                        $data['fax_number']=(isset($input['fax_number']) ? $input['fax_number'] : '');
                        $data['web_site']=(isset($input['web_site']) ? $input['web_site'] : '');
                        $data['telephone_number_of_primary_contact']=$input['telephone_number_of_primary_contact'];
                        $data['mobile_number_of_primary_contact']=$input['mobile_number_of_primary_contact'];
                        $data['email_of_primary_contact']=$input['email_of_primary_contact'];
                        $data['distributor_code']=$input['distributor_code'];
                        Mail::to($input['email_of_primary_contact'])->queue(new SendDynamicEmail($data));

                        $moduleName='Email';
                        $moduleActivity='Email logged for Distributor Updated';
                        $description = 'Email has been sent to Distributor primary user: '.ucfirst($input['name_of_primary_contact']);
                        $requestData = array('distributor_id'=>$distributor->id);

                        /*Add action in audit log*/
                         captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                         /*Add action in audit log*/

                           /* AT-2049 - Send EMAIL if email change */
                    $is_email_change = 0;
                    if($oldEmail != $input['email_of_primary_contact'] ){
                        $data = array();
                        $data['slug'] = 'email_change_on_profile_update';
                        $data['name'] = $input['full_company_name'];
                        $data['email'] = $input['email_of_primary_contact'];
                        $data['old_email'] = $oldEmail;
                        $data['primary_telephone_number'] = $input['telephone_number_of_primary_contact'];
                        $data['mobile_telephone_number'] = $input['mobile_number_of_primary_contact'];
                        Mail::to($oldEmail)->queue(new SendDynamicEmail($data));
                        $is_email_change = 1;

                        $moduleName='Email';
                        $moduleActivity='Email logged for Distributor primary user has changed email';
                        $description = 'Email has been sent to Distributor primary user: '.ucfirst($input['name_of_primary_contact']);
                        $requestData = array('distributor_id'=>$distributor->id);

                        /*Add action in audit log*/
                            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                        /*Add action in audit log*/
                    }

                    /* AT-2049 - Send SMS */
                    if($is_email_change == 1){
                        /* AT-2049 - Send SMS */
                        $template_slug = 'email_update';
                        $recipient = $input['mobile_number_of_primary_contact'];
                        SendTwilioSMS($recipient, $template_slug);
                    }else{
                        /* AT-2049 - Send SMS */
                        $template_slug = 'user_details_update';
                        $recipient = $input['mobile_number_of_primary_contact'];
                        SendTwilioSMS($recipient, $template_slug);
                    }
                        $message='Distributor updated successfully';

                        $response= [
                            'success'=>true,
                            'message'=>$message,
                            'last_updated_id'=>$distributor->id
                        ];
                        return response()->json($response,$this->successStatus);
                    }else{
                        $message='Something wrong please try again';
                        $response = [
                            'success'=>'fail',
                            'message'=> $message,
                        ];
                        return response()->json($response,500);
                    }
                    /* update the distributor */
                }
            }
            public function suspendDistributor(Request $request,$id){
                $distributor=Distributor::find($id);
                if($distributor){
                    $getFullCompnay= ucfirst($distributor->full_company_name);
                    $distributor->status=3;
                    $distributor=$distributor->save();
                    if($distributor){

                            /*Add action in audit log*/
                            $moduleName='distributor';
                            $moduleActivity='Distributor Suspended';
                            // $description=ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has suspended ' .$getFullCompnay ." Distributor.";
                            $description= "Distributor ".$getFullCompnay." has been suspended";
                            $inputData = array('distributor_id'=>$id);
                            $usercompanyName = getUserCompanyName(Auth::user());
                            captureAuditLog($moduleName,$moduleActivity,$description,$inputData,$usercompanyName);
                            /*Add action in audit log*/

                            $distributor_record=Distributor::find($id);
                            $data['slug'] = 'distributor_suspended';
                            $data['name_of_primary_contact'] = $distributor_record->name_of_primary_contact;
                            $data['full_company_name'] = $distributor_record->full_company_name;
                            Mail::to($distributor_record->email_of_primary_contact)->queue(new SendDynamicEmail($data));


                            $moduleName = 'Email';
                            $moduleActivity = 'Email logged for Distributor Suspended';
                            $description = 'Email has been sent to Distributor primary user: '.ucfirst($distributor_record->name_of_primary_contact);
                            $requestData = array('distributor_id'=>$distributor_record->id);

                            /*Add action in audit log*/
                                captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                            /*Add action in audit log*/

                            $getAllUsers =getDistributorCompanyAllUsersByCompanyId($id);
                            if(!empty($getAllUsers)){
                                suspendOrReleseCompanyUsers($getAllUsers,'suspend');
                            }

                            $distributorUser=DistributorUser::where('fk_distributor_id',$id)->pluck('fk_user_id');
                            $user=User::whereIn('id',$distributorUser)->update(['status'=>3,'is_logged_in'=>0]);

                        $message='Distributor suspended successfully';
                        $response=[
                            'success'=>'true',
                            'message'=>$message,

                        ];
                        return response()->json($response,$this->successStatus);
                    }else{
                        $message='Something wrong please try again';
                        $response=[
                            'success'=>'fail',
                            'message'=>$message,
                        ];
                        return response()->json($response,500);
                    }
                }else{
                    return response()->json(['message'=>'No Any distributor found.']);
                }
            }

            public function releaseDistributor(Request $request,$id){
                $distributor=Distributor::find($id);

                if($distributor){
                    $getFullCompnay = ucfirst($distributor->full_company_name);
                    $distributor->status=1;
                    $distributor=$distributor->save();

                    if($distributor){

                        /*Add action in audit log*/
                        $moduleName='distributor';
                        $moduleActivity='Distributor Released';
                        // $description=ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has released ' .$getFullCompnay ." Distributor.";
                        $description= "Distributor ".$getFullCompnay." has been released";
                        $inputData = array('distributor_id'=>$id);
                        $usercompanyName = getUserCompanyName(Auth::user());
                        captureAuditLog($moduleName,$moduleActivity,$description,$inputData,$usercompanyName);
                        /*Add action in audit log*/
                        $distributor_record=Distributor::find($id);

                        $data['slug'] = 'distributor_released';
                        $data['name_of_primary_contact'] = $distributor_record->name_of_primary_contact;
                        $data['full_company_name'] = $distributor_record->full_company_name;
                        Mail::to($distributor_record->email_of_primary_contact)->queue(new SendDynamicEmail($data));

                        $moduleName = 'Email';
                        $moduleActivity = 'Email logged for Distributor Released';
                        $description = 'Email has been sent to Distributor primary user: '.ucfirst($distributor_record->name_of_primary_contact);
                        $requestData = array('distributor_id'=>$distributor_record->id);

                        /*Add action in audit log*/
                            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                        /*Add action in audit log*/


                        $getAllUsers =getDistributorCompanyAllUsersByCompanyId($id);
                        if(!empty($getAllUsers)){
                            suspendOrReleseCompanyUsers($getAllUsers,'relese');
                        }
                        $distributorUser=DistributorUser::where('fk_distributor_id',$id)->pluck('fk_user_id');
                        $user=User::whereIn('id',$distributorUser)->update(['status'=>1]);


                        $message='Distributor released successfully';
                        $response=[
                            'success'=>'true',
                            'message'=>$message,
                        ];
                        return response()->json($response,$this->successStatus);
                    }else{
                        $message='something wrong please try again';
                        $response=[
                            'success'=>'fail',
                            'message'=>$message,
                        ];
                        return response()->json($response,500);
                    }
                }else{
                    return response()->json(['message'=>'this id not found in database']);
                }
            }

            public function deleteDistributor(Request $request,$id){
                $distributor=Distributor::find($id);
                $getFullCompnay = ucfirst($distributor->full_company_name);
                if($distributor){
                    $distributor->status=2;
                    $distributor=$distributor->save();
                    if($distributor){

                        $moduleName='distributor';
                        $moduleActivity='Distributor Deleted';
                        // $description=ucfirst(Auth::user()->name)." (".getUserRoles(Auth::user()->roles->first()->name).') has deleted ' .$getFullCompnay ." Distributor.";
                        $description= "Distributor ".$getFullCompnay." has been deleted";
                        $inputData = array('distributor_id'=>$id);
                        $usercompanyName = getUserCompanyName(Auth::user());

                        /*Add action in audit log*/
                        captureAuditLog($moduleName,$moduleActivity,$description,$inputData,$usercompanyName);
                        /*Add action in audit log*/

                        $distributor_record=Distributor::find($id);
                        $data['slug'] = 'distributor_deleted';
                        $data['name_of_primary_contact'] = $distributor_record->name_of_primary_contact;
                        $data['full_company_name'] = $distributor_record->full_company_name;
                        Mail::to($distributor_record->email_of_primary_contact)->queue(new SendDynamicEmail($data));

                        $moduleName='Email';
                        $moduleActivity='Email logged for Distributor Deleted';
                        $description = 'Email has been sent to Distributor primary user: '.ucfirst($distributor_record->name_of_primary_contact);
                        $requestData = array('distributor_id'=>$distributor_record->id);

                        /*Add action in audit log*/
                        captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
                        /*Add action in audit log*/

                        $message='Distributor deleted successfully';

                        $response=[
                            'success'=>'true',
                            'message'=>$message,
                        ];
                        return response()->json($response,$this->successStatus);
                    }else{
                        $message='Something wrong please try again';
                        $response=[
                            'success'=>'fail',
                            'message'=>$message,
                        ];
                        return response()->json($response,500);
                    }
                }else{
                    return response()->json(['message'=>'Distributor not found']);
                }
            }

            public function distributorCode(Request $request){
                if($request->ajax()){
                    if($request->id){
                        $distributor=Distributor::where('distributor_code',$request->distributor_code)
                        ->where('id','<>',$request->id)
                        ->where('status','!=',2)
                        ->first();
                    }else{
                        $distributor=Distributor::where('distributor_code',$request->distributor_code)
                        ->where('status','!=',2)
                        ->first();
                    }
                    if($distributor){
                        return response()->json(false);
                    }else{
                        return response()->json(true);
                    }
                }
            }

            public function distributorView($internal_id){
                $data['distributor']=Distributor::where('internal_id',$internal_id)->with('country')->first();

                if($data['distributor']){
                    return view('distributor.distributor-user',$data);
                }else{
                    $notification = array(
                        'message' => 'No Distributor Found',
                        'alert-type' => 'error'
                    );
                    return redirect('distributor')->with($notification);
                }
            }

            public function verifyEmail(Request $request){
                if ($request->hasValidSignature()) {
                    $email = $request->query('email');
                    $distributor = Distributor::where('email_of_primary_contact',$email)->where('status','!=','2')->first();

                    if($distributor && $distributor->count() > 0){
                        if($distributor->is_verified != 0){
                            $message = "Your email has been already verified";
                            $notification = array(
                                'message' => $message,
                                'alert-type' => 'error'
                            );
                        }else{
                            $distributor_verify = Distributor::find($distributor->id);
                            $distributor_verify->is_verified = 1;
                            $distributor_verify->verified_at = new DateTime;
                            $distributor_verify->save();
                            $message = "Your email has been verified";
                            $notification = array(
                                'message' => $message,
                                'alert-type' => 'success'
                            );
                        }
                    }else{
                        $message = "Your link has been expired or invalid";
                        $notification = array(
                            'message' => $message,
                            'alert-type' => 'error'
                        );
                    }
                }else{
                    $message = "Your link has been expired or invalid";
                    $notification = array(
                        'message' => $message,
                        'alert-type' => 'error'
                    );
                }

                return redirect('/login')->with($notification);
            }
        }