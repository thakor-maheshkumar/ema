<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TreatmentCenter;
use App\HydraCoolSrp;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class DeviceController extends Controller
{


	/**
	* load all hydracool srp off all treatment center
	*/
	public function hydracoolSrpList(Request $request)
	{

		if($request->ajax()){
			$searchData =$request->get('search_data');
			if(isset($searchData) && !empty($searchData)){
				$hydracoolSRPData = HydraCoolSrp::getHydraCoolSRPListWithTreatmentCenter($searchData);
				$hydracoolData = array();
				if(!empty($hydracoolSRPData)){
					foreach($hydracoolSRPData as $key=>$hydracoolSRPRow){
						$hydracoolData[$key]['full_company_name'] = $hydracoolSRPRow['full_company_name'];
						$hydracoolData[$key]['serial_number'] = $hydracoolSRPRow['serial_number'];
						$hydracoolData[$key]['last_active'] = 0;
						$hydracoolData[$key]['install_device'] = 0;
						$hydracoolData[$key]['last_seven'] = 0;
						$hydracoolData[$key]['last_30_days'] = 0;
						$hydracoolData[$key]['last_90_days'] = 0;
						$hydracoolData[$key]['last_12_month'] = 0;
						$hydracoolData[$key]['total_treatment'] = 0;
						// $hydracoolData[$key]['created_at'] = $hydracoolSRPRow['created_at'];
					}
				}
				$data = makeNumericArray($hydracoolData);
				return $data;
			}else{
				$getHydracoolSRPList = HydraCoolSrp::getHydraCoolSRPListWithTreatmentCenter();
				if(!empty($getHydracoolSRPList)){
					foreach($getHydracoolSRPList as $key=>$hydracoolSRPRow){
						$hydracoolSRPRow['full_company_name'] = $hydracoolSRPRow->full_company_name;
						$hydracoolSRPRow['serial_number'] = $hydracoolSRPRow->serial_number;
						$hydracoolSRPRow['last_active'] = 0;
						$hydracoolSRPRow['install_device'] = 0;
						$hydracoolSRPRow['last_seven'] = 0;
						$hydracoolSRPRow['last_30_days'] = 0;
						$hydracoolSRPRow['last_90_days'] = 0;
						$hydracoolSRPRow['last_12_month'] = 0;
						$hydracoolSRPRow['total_treatment'] = 0;
						$hydracoolSRPRow['created_at'] = $hydracoolSRPRow->created_at;
						$getHydracoolSRPList[$key]=$hydracoolSRPRow;
					}
				}
				// Get active hydracool srp
				return Datatables::of($getHydracoolSRPList)
				->addColumn('action', function ($getHydracoolSRPRow) {
					$authUser = Auth::user();
					$edit = $view = $delete = '';
					if($getHydracoolSRPRow->is_ema==1){
						if($authUser->hasRole(['system administrator','ema service support'])){
							$delete='<a href="javascript:;" class="deleteHydracoolsrp" title="Delete Device" data-hydracoolsrpId='.$getHydracoolSRPRow->id.' data-hydracoolsrpSerialNumber="'.ucfirst($getHydracoolSRPRow->serial_number).'"><i class="far fa-trash-alt"></i></a>';
						}
					}
					if($authUser->hasRole(['system administrator','ema service support','distributor principal'])){
						$edit='<a href="javascript:;" class="edithydracoolsrp" title="Edit Device" data-hydracoolsrpId='.$getHydracoolSRPRow->id.'><i class="far fa-edit"></i></a>';
					}

					if($authUser->hasRole(['system administrator','ema analyst','ema service support','treatment centre manager','distributor principal','distributor service','distributor sales'])){
						$view = '<a href="javascript:;" class="viewHydracoolsrp" title="View Device" data-hydracoolsrpId='.$getHydracoolSRPRow->id.'><i class="far fa-eye"></i></a>';
					}
					return $edit.$view.$delete;

				})
				->make(true);
			}
		}else{
			$authUser = Auth::user();
			$getDistributorCompanyDataById = getDistributorCompanyDataById();
			$getTreatmentCenterList = TreatmentCenter::where('status','1');
			if($authUser->hasRole(['distributor principal'])){
				$getTreatmentCenterList->where('distributors',$getDistributorCompanyDataById->distributor_company_id);
			}
			$getTreatmentCenterList = $getTreatmentCenterList->get();
			$getHydraCoolSrpData = array();

			/*Get hydracool srp serial number*/
		$getHydraCoolSrpObj = HydraCoolSrp::getHydraCoolSrpSerialNumber();
		if(!empty($getHydraCoolSrpObj)){
			$getHydraCoolSrpData = $getHydraCoolSrpObj;
		}
			return view('device.devices',compact('getTreatmentCenterList','getHydraCoolSrpData'));
		}
	}
}
