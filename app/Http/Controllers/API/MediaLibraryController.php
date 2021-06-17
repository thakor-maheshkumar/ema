<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ServiceSupportCategory;
use App\MediaLibrary;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\HydraCoolSrp;

class MediaLibraryController extends Controller
{
    /**
    *  Load media library list data
    */
    public function mediaLibraryList(Request $request)
    {
        if($request->wantsJson()){
            $getMediaListData = MediaLibrary::getMediaLibraryList();
            return Datatables::of($getMediaListData)
            ->addColumn('action', function ($getMediaListRow) {
                $authUser = Auth::user();
                $download = $delete = '';
                if($authUser->hasRole(['system administrator','ema analyst','ema service support','distributor principal','treatment centre manager','distributor service','distributor sales'])){
                    $download =  '<a href='.route('download').'?filename='.$getMediaListRow['document_url'].' title="Download Document"><i class="fas fa-download"></i></a>';
                }
                if($authUser->hasRole(['system administrator'])){
                    $delete = '<a href="javascript:;" class="deletemediafile" data-mediaFileId="'.$getMediaListRow['id'].'" data-mediaFileDocumentName="'.ucfirst($getMediaListRow['document_name']).'" data-FileName="'.$getMediaListRow['document_url'].'" title="Delete Document"><i class="far fa-trash-alt"></i></a>';
                }
                return $download.$delete;
            })
            ->editColumn('status', function ($getMediaListData){
                    if($getMediaListData->status==1){
                         $getMediaListData->status="Active";
                    }else if($getMediaListData->status==2){
                        $getMediaListData->status="Delete";
                    }else if($getMediaListData->status==3){
                        $getMediaListData->status="Suspend";
                    }
                    return $getMediaListData->status;
            })
            ->editColumn('created_at', function ($getMediaListData){
                return $getMediaListData->created_at ? with(new Carbon($getMediaListData->created_at))->format('m-d-Y H:i:s') : '';
            })
            ->make(true);

        }else{
        /* Get media library category list */
        $getSupportCategory = ServiceSupportCategory::all();
        $getHydraCoolSrpSerial=HydraCoolSrp::where('status',1)->get();
        /* Get media library category list */
        }
        return view('media-library/media-library',compact('getSupportCategory','getHydraCoolSrpSerial'));
    }
    /**
    *  Save media library information
    */
    public function saveMediaLibraryData(Request $request)
    {
        $userInput = array();
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'document_name' => ['required', 'string','max:255'],
            'category_id' =>['required'],
            'fk_hydracool_srp_id' =>['required'],
            'description'=>['required'],
            ]);
       if ($validator->fails()) {
           return response()->json(['errors'=>$validator->errors()], 422);
       }else{

           /* upload file in s3 bucket */
           $path = Storage::disk('s3')->put('mediafiles',$requestData['file']);
           /* upload file in s3 bucket */

           $saveData = new MediaLibrary;
           $saveData->document_name =$requestData['document_name'];
           $saveData->category_id =$requestData['category_id'];
           $saveData->fk_hydracool_srp_id  =$requestData['fk_hydracool_srp_id'];
           $saveData->document_url =$path;
           $saveData->description=$requestData['description'];
           $saveData->created_by =Auth::user()->id;
           $saveData->ip_address =request()->ip();
           $saveData->save();
           if($saveData){
               $moduleName = "media library";
               $moduleActivity = "Added media library document";
               $usercompanyName = getUserCompanyName(Auth::user());
            //    $description = Auth::user()->name.' ('.getUserRoles(Auth::user()->roles->first()->name).') has added media library with'.$request['device_name']." device.";
               $description = "Document ".ucfirst($request['fk_hydracool_srp_id'])." added to Media Library";

               /*Add action in audit log*/
               captureAuditLog($moduleName,$moduleActivity,$description,$saveData,$usercompanyName);
               /*Add action in audit log*/

               $message = 'Media library document added successfully';
               $response = [
                'success' => 'true',
                'message' => $message,
            ];

            return response()->json($response, 200);

           }else{
            $message = 'Something wrong please try again';
            $response = [
                'success' => 'false',
                'message' => $message,
            ];
            return response()->json($response, 500);
           }
        }
    }

    /**
    *  Download media file
    */
    public function downloadMediaFile(Request $request)
    {
        try{
            $file_name = $request->get('filename');

            $mime = Storage::disk('s3')->getDriver()->getMimetype($file_name);
            $size = Storage::disk('s3')->getDriver()->getSize($file_name);
            $response =  [
                'Content-Type' => $mime,
                'Content-Length' => $size,
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$file_name}",
                'Content-Transfer-Encoding' => 'binary',
            ];
            ob_end_clean();
            return \Response::make(Storage::disk('s3')->get($file_name), 200, $response);
        }
        catch(Exception $e){
            return $this->respondInternalError( $e->getMessage(), 'object', 500);
        }
    }

    /**
    * Delete support document from s3 and from db as well
    */
    public function deleteMediaLibraryData(Request $request)
    {
        $requestData = $request->all();
        $getMediaLibraryId = $request->input('media_library_id');
        $getMediaLibraryFileName = $request->input('filename');

        $checkDocumentExists =  MediaLibrary::find($getMediaLibraryId);
        if($checkDocumentExists){

            /* Delete from db */
            MediaLibrary::where('id',$getMediaLibraryId)->delete();
            /* Delete from db */

            $moduleName = "media library";
            $moduleActivity = "Delete media library document";
            $usercompanyName = getUserCompanyName(Auth::user());
            // $description = Auth::user()->name.' ('.getUserRoles(Auth::user()->roles->first()->name).') has deleted media library with'.$checkDocumentExists['device_name']." device.";
            $description = "Document ".ucfirst($checkDocumentExists['fk_hydracool_srp_id'])." deleted from Media Library";

           /*Add action in audit log*/
            captureAuditLog($moduleName,$moduleActivity,$description,$requestData,$usercompanyName);
            /*Add action in audit log*/

            /* remove the file from AWS s3 */
            Storage::disk('s3')->delete($getMediaLibraryFileName);
            /* remove the file from AWS s3 */

            $message = 'Support document deleted successfully';
            $response = [
                'success' => 'true',
                'message' => $message,
            ];
         return response()->json($response, 200);

        }else{
            $message = 'Support document not found';
            $response = [
                'success' => 'false',
                'message' => $message,
            ];
         return response()->json($response, 500);
        }
    }
}