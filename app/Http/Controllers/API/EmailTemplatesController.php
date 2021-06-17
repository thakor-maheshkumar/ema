<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TreatmentCenter;
use App\HydraCoolSrp;
use Yajra\DataTables\Facades\DataTables;
use App\EmailTemplates;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Str;

class EmailTemplatesController extends Controller{

	public $successStatus = 200;

	/**
	* load all emailTemplate
	*/
	public function emailTemplate(Request $request)
	{
		if($request->wantsJson()){
			$EmailTemplatesData = EmailTemplates::getEmailTemplateList();
			return Datatables::of($EmailTemplatesData)
			->addColumn('action', function ($getEmailTemplatesRow) {
				return '<a href="javascript:;" class="editEmailTemplate" title="Edit" data-EmailTemplatesId='.$getEmailTemplatesRow->id.'><i class="far fa-edit"></i></a>
				<a href="javascript:;" class="deleteEmailTemplate" title="Delete" data-EmailTemplatesId='.$getEmailTemplatesRow->id.'><i class="far fa-trash-alt"></i></a>';
			})->make(true);
		}else{
			return view('emailtemplate.index');
		}
	}

	public function createEmailTemplate(Request $request){
		$request->validate([
			'email_template_name' => ['required'],
			'email_template_subject' => ['required'],
			'email_template_content' => ['required'],
		]);

		if($request->hidden_email_template_id){
			$emailTemplates = EmailTemplates::find($request->hidden_email_template_id);
			$emailTemplates->updated_at = new DateTime;
		}else{
			$emailTemplates = new EmailTemplates;
			$emailTemplates->created_at = new DateTime;
			$emailTemplates->slug = Str::slug($request->email_template_slug, '_');
		}
		$emailTemplates->name = $request->email_template_name;
		$emailTemplates->subject = $request->email_template_subject;
		$emailTemplates->content = $request->email_template_content;
		$emailTemplates->save();

		if($request->hidden_email_template_id){
			$moduleName = 'Email Template';
			$moduleActivity = 'Update Email Template.';
			$description = auth()->user()->username.' has update email template '.$emailTemplates->name;
			/*Start - Add action in audit log*/
			//captureAuditLog($moduleName,$moduleActivity,$description,$request->all());
			/*End - Add action in audit log*/
			$notification = array(
				'message' => 'Email template has been updated.',
				'alert-type' => 'success'
			);
		}else{
			$moduleName = 'Email Template';
			$moduleActivity = 'New Email Template.';
			$description = auth()->user()->username.' has created new email template.';
			/*Start - Add action in audit log*/
			captureAuditLog($moduleName,$moduleActivity,$description,$request->all());
			/*End - Add action in audit log*/
			$notification = array(
				'message' => 'New email template has been created.',
				'alert-type' => 'success'
			);
		}
		return redirect()->back()->with($notification);
	}

	/**
	* Get the EmailTempalte Details by id
	* @return json
	*/
	public function getEmailTempalteDetails(Request $request){
		$request->validate([
			'EmailTemplatesId' => ['required','numeric']
		]);

		$EmailTemplatesId = $request->input('EmailTemplatesId');

		/* get Email Template data */
		$getEmailTemplatesData = EmailTemplates::getEmailTemplateDetails($EmailTemplatesId);
		/* get Email Template data */

		if(!empty($getEmailTemplatesData)){
			$message = 'Email Template details';
			$response = [
				'success' => 'true',
				'message' => $message,
				'name'=>$getEmailTemplatesData['name'],
				'slug'=>$getEmailTemplatesData['slug'],
				'subject'=>$getEmailTemplatesData['subject'],
				'content'=>$getEmailTemplatesData['content']
			];
		}else{
			$message = 'No email template found';
			$response = [
				'success' => 'fail',
				'message' => $message,
			];
		}
		return response()->json($response, $this->successStatus);
	}

	public function deleteEmailTemplate(Request $request){
		$userInput = array();
		$input = $request->all();
		$EmailTemplatesId = 	$input['EmailTemplatesId'];

		$validator = Validator::make($request->all(), [
			'EmailTemplatesId' =>['required','numeric'],
		]);

		if ($validator->fails()) {
			return response()->json(['error'=>$validator->errors()], 401);
		}else{

			/*delete Email template*/
			$EmailTemplates = EmailTemplates::find($EmailTemplatesId);
			if(isset($EmailTemplates) && !empty($EmailTemplates)){
				$EmailTemplates->status=2;
				$EmailTemplates->save();
				if($EmailTemplates){
					$moduleName = 'Email Template';
					$moduleActivity = 'delete Email Template';
					$description =  auth()->user()->username.' has deleted Email Template '.$EmailTemplates->name;
					/*Add action in audit log*/
					//captureAuditLog($moduleName,$moduleActivity,$description,$input);
					/*Add action in audit log*/
					$message = 'Email template has been delete successfully';
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
				$message = 'Email template not found';
				$response = [
					'success' => 'fail',
					'message' => $message,
				];
				return response()->json($response, 404);
			}
			/*delete Email template*/
		}
	}
	public function checkExistingSlug(Request $request){
        if($request->ajax()){
        	$slug = Str::slug($request->email_template_slug, '_');
            $email_template_slug=EmailTemplates::where('slug',$slug)->first();
            if($email_template_slug){
                return response()->json(false);
            }else{
                return response()->json(true);
            }
        }
    }
}
