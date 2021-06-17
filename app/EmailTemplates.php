<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;


class EmailTemplates extends Model
{
    public function parse($data)
	{
		$parsed = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($data) {
			list($shortCode, $index) = $matches;

			if( isset($data[$index]) ) {
				return $data[$index];
			} else {
				throw new Exception("Shortcode {$shortCode} not found in template id {$this->id}", 1);   
			}

		}, $this->content);

		return $parsed;
	}

	static function getEmailTemplateList(){
        $getEmailTemplateListData = EmailTemplates::where('status',1)
                                                ->orderby('id','desc')
                                                ->get();
        if($getEmailTemplateListData){
            foreach($getEmailTemplateListData as $key=>$getEmailTemplateListRow){
                if($getEmailTemplateListRow['status']==1){
                    $getEmailTemplateListRow['status']="Active";
                }else if($getEmailTemplateListRow['status']==2){
                    $getEmailTemplateListRow['status']="Delete";
                }                
                $getEmailTemplateListData[$key] = $getEmailTemplateListRow;
            }
        }
        return $getEmailTemplateListData;
    }

    static function getEmailTemplateDetails($id){
        $getEmailTemplateData = EmailTemplates::where('id',$id)->first();        
        return $getEmailTemplateData;
    }

}
