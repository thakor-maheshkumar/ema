<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TreatmentCenter;
use App\HydraCoolSrp;
use Yajra\DataTables\Facades\DataTables;
use App\SMSTemplate;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Str;

class SMSTemplatesController extends Controller{

	public $successStatus = 200;

	/**
	* load all smsTemplate
	*/
	public function SMSTemplate(Request $request)
	{
		if($request->wantsJson()){
			$SMSTemplatesData = SMSTemplate::getSMSTemplateList();
			return Datatables::of($SMSTemplatesData)
			->addColumn('action', function ($getSMSTemplatesRow) {
				return '<a href="javascript:;" class="editSMSTemplate" title="Edit" data-SMSTemplatesId='.$getSMSTemplatesRow->id.'><i class="far fa-edit"></i></a>
				<a href="javascript:;" class="deleteSMSTemplate" title="Delete" data-SMSTemplatesId='.$getSMSTemplatesRow->id.'><i class="far fa-trash-alt"></i></a>';
			})->make(true);
		}else{
			return view('smstemplate.index');
		}
	}

	public function createSMSTemplate(Request $request){
		$request->validate([
			'sms_template_name' => ['required'],
			'sms_template_content' => ['required'],
		]);

		if($request->hidden_sms_template_id){
			$smsTemplates = SMSTemplate::find($request->hidden_sms_template_id);
			$smsTemplates->updated_at = new DateTime;
		}else{
			$smsTemplates = new SMSTemplate;
			$smsTemplates->created_at = new DateTime;
			$smsTemplates->slug = Str::slug($request->sms_template_slug, '_');
		}
		$smsTemplates->name = $request->sms_template_name;
		$smsTemplates->content = $request->sms_template_content;		
		$smsTemplates->save();

		if($request->hidden_sms_template_id){
			$moduleName = 'SMS Template';
			$moduleActivity = 'Update SMS Template.';
			$description = auth()->user()->username.' has update sms template '.$smsTemplates->name;
			/*Start - Add action in audit log*/
			captureAuditLog($moduleName,$moduleActivity,$description,$request->all());
			/*End - Add action in audit log*/
			$notification = array(
				'message' => 'SMS template has been updated.',
				'alert-type' => 'success'
			);
		}else{
			$moduleName = 'SMS Template';
			$moduleActivity = 'New SMS Template.';
			$description = auth()->user()->username.' has created new sms template.';
			/*Start - Add action in audit log*/
			captureAuditLog($moduleName,$moduleActivity,$description,$request->all());
			/*End - Add action in audit log*/
			$notification = array(
				'message' => 'New sms template has been created.',
				'alert-type' => 'success'
			);
		}		
		return redirect()->back()->with($notification);
	}

	/**
	* Get the SMSTempalte Details by id
	* @return json
	*/
	public function getSMSTemplateDetails(Request $request){
		$request->validate([
			'SMSTemplatesId' => ['required','numeric']
		]);

		$SMSTemplatesId = $request->input('SMSTemplatesId');

		/* get SMS Template data */
		$getSMSTemplatesData = SMSTemplate::getSMSTemplateDetails($SMSTemplatesId);
		/* get SMS Template data */

		if(!empty($getSMSTemplatesData)){
			$message = 'SMS Template details';
			$response = [
				'success' => 'true',
				'message' => $message,
				'name'=>$getSMSTemplatesData['name'],
				'slug'=>$getSMSTemplatesData['slug'],				
				'content'=>$getSMSTemplatesData['content']
			];
		}else{
			$message = 'No sms template found';
			$response = [
				'success' => 'fail',
				'message' => $message,
			];
		}
		return response()->json($response, $this->successStatus);
	}

	public function deleteSMSTemplate(Request $request){
		$userInput = array();
		$input = $request->all();
		$SMSTemplatesId = 	$input['SMSTemplatesId'];

		$validator = Validator::make($request->all(), [
			'SMSTemplatesId' =>['required','numeric'],
		]);

		if ($validator->fails()) {
			return response()->json(['error'=>$validator->errors()], 401);
		}else{

			/*delete SMS template*/
			$SMSTemplates = SMSTemplate::find($SMSTemplatesId);
			if(isset($SMSTemplates) && !empty($SMSTemplates)){
				$SMSTemplates->status=2;
				$SMSTemplates->save();
				if($SMSTemplates){
					$moduleName = 'SMS Template';
					$moduleActivity = 'delete SMS Template';
					$description =  auth()->user()->username.' has deleted SMS Template '.$SMSTemplates->name;
					/*Add action in audit log*/
					captureAuditLog($moduleName,$moduleActivity,$description,$input);
					/*Add action in audit log*/
					$message = 'SMS template has been delete successfully';
					$response = [
						'success' => 'true',
						'message' => $message,
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
			}else{
				$message = 'SMS template not found';
				$response = [
					'success' => 'fail',
					'message' => $message,
				];
				return response()->json($response, 404);
			}
			/*delete SMS template*/
		}
	}
	public function checkExistingSMSSlug(Request $request){
        if($request->ajax()){
        	$slug = Str::slug($request->sms_template_slug, '_');
            $sms_template_slug=SMSTemplate::where('slug',$slug)->first();           
            if($sms_template_slug){
                return response()->json(false);
            }else{
                return response()->json(true);
            }
        }
    }
}
