<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class MediaLibrary extends Model
{
    protected $table = 'support_documents';

    /**
    *  get media library list data
    */

    static function getMediaLibraryList(){
        $getMediaLibraryListData = MediaLibrary::join('service_support_category as category','category.id','=','support_documents.category_id')
                                                ->join('hydracool_srp as h','h.id','=','support_documents.fk_hydracool_srp_id')
                                                ->select('category.category_name','support_documents.*','h.serial_number')
                                                ->where('support_documents.status',1)
                                                ->orderby('support_documents.id','desc');
        //                                         ->get();
        // if($getMediaLibraryListData){
        //     foreach($getMediaLibraryListData as $key=>$getMediaLibraryListRow){
        //         if($getMediaLibraryListRow['status']==1){
        //             $getMediaLibraryListRow['status']="Active";
        //         }else if($getMediaLibraryListRow['status']==2){
        //             $getMediaLibraryListRow['status']="Delete";
        //         }else if($getMediaLibraryListRow['status']==3){
        //             $getMediaLibraryListRow['status']="Suspend";
        //         }
        //         $getMediaLibraryListData[$key] = $getMediaLibraryListRow;
        //     }
        // }
        return $getMediaLibraryListData;
    }
}
