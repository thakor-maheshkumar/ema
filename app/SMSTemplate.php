<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMSTemplate extends Model
{
    protected $table = 'sms_templates';

    static function getSMSTemplateList(){
        $getSMSTemplateListData = SMSTemplate::where('status',1)
                                                ->orderby('id','desc')
                                                ->get();
        if($getSMSTemplateListData){
            foreach($getSMSTemplateListData as $key=>$getSMSTemplateListRow){
                if($getSMSTemplateListRow['status']==1){
                    $getSMSTemplateListRow['status']="Active";
                }else if($getSMSTemplateListRow['status']==2){
                    $getSMSTemplateListRow['status']="Delete";
                }                
                $getSMSTemplateListData[$key] = $getSMSTemplateListRow;
            }
        }
        return $getSMSTemplateListData;
    }

    static function getSMSTemplateDetails($id){
        $getSMSTemplateData = SMSTemplate::where('id',$id)->first();        
        return $getSMSTemplateData;
    }
}
