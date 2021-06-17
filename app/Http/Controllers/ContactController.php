<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactData;
use Validator;
use Mail;
use App\Mail\SendDynamicEmail;
use App\CoreSetting;

class ContactController extends Controller
{
    public function storeContact(Request $request){
        $input=$request->all();
        $validation=Validator::make($request->all(),[
            'first_name'=>['required'],
            'last_name'=>['required'],
            'message'=>['required']
        ]);
        $contactData=new ContactData;
        $contactData->first_name=$input['first_name'];
        $contactData->last_name=$input['last_name'];
        $contactData->company_name=(isset($input['company_name']) ? $input['company_name'] : '');
        $contactData->job_role=(isset($input['job_role']) ? $input['job_role'] : '');

        $contactData->country_id=(isset($input['country_id']) ? $input['country_id'] : null);
        $contactData->email_address=(isset($input['email_address']) ? $input['email_address'] : '');
        $contactData->contact_telephone_number=(isset($input['contact_telephone_number']) ? $input['contact_telephone_number'] : '');
        $contactData->mobile_number=(isset($input['mobile_number']) ? $input['mobile_number'] : '');
        $contactData->message=$request->message;
        $contactData->ip_address=request()->ip();
        $contactData->save();

        if($contactData->email_address){
            $contactDatas=$contactData->toArray();
            $data= array();
            $data['slug'] = 'contact_us';
            $data['first_name'] = $contactData->first_name;

            Mail::to($contactData->email_address)->queue(new SendDynamicEmail($data));
        }
        ///Email Process ////


        $dataadmin= array();
        $dataadmin['slug'] = 'admin_contact_us';
        $dataadmin['first_name'] = $contactData->first_name;
        $dataadmin['last_name'] = $contactData->last_name;
        $dataadmin['company_name'] = $contactData->company_name;
        $dataadmin['job_role'] = $contactData->job_role;
        $dataadmin['country_id'] = $contactData->country_id;
        $dataadmin['email_address'] = $contactData->email_address;
        $dataadmin['contact_telephone_number'] = $contactData->contact_telephone_number;
        $dataadmin['mobile_number'] = $contactData->mobile_number;
        $dataadmin['message'] = $contactData->message;

        $coresetting=CoreSetting::where('name','contact_forward_email')->get();

        Mail::to($coresetting[0]->value)->queue(new SendDynamicEmail($dataadmin));

        $moduleName = 'Contact EMA';
        $moduleActivity = 'Add contact detail';
        // $description = $contactData->first_name.' '.$contactData->last_name. ' has submitted his details.';
        $description = "Contact details for user ".ucfirst($contactData->first_name)." ".$contactData->last_name." is added";
        /*Start - Add action in audit log*/
        captureAuditLog($moduleName,$moduleActivity,$description,$request->all());
         /*End - Add action in audit log*/
        $notification = array(
            'contact-message' => 'Thank you for submitting details.',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
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
    public function help()
    {
        return view('help');
    }
}
