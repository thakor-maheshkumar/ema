<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CosmeticDeliveries extends Model
{
    protected $table='cosmetic_deliveries';

    static function getCosmeticList($searchData=null,$treatmentcentre_id,$cometic_id=null)
    {
        if(!empty($searchData)){
            $getCosmeticDeliveries = CosmeticDeliveries::select('solution_bottle_pack','solution_1','solution_2','solution_3','solution_4','cosmetic_fresh_pack','cosmetic_bright_pack','booster_packs','aquaB_tips','delivery_date');
        }else{
            $getCosmeticDeliveries = CosmeticDeliveries::select('*');
        }
        $getCosmeticDeliveries->whereIn('status',[1,3]);
        $getCosmeticDeliveries->where('fk_treatment_centers_id',$treatmentcentre_id);
        if(!empty($searchData)){
            foreach($searchData as $key=>$val){
                if($val){
                    $getCosmeticDeliveries->where($key,'LIKE',"%".$val."%");
                }
            }
        }
        if(!empty($cometic_id)){
            $getCosmeticDeliveries->where('id',$cometic_id);
        }
        if(!empty($searchData)){
            $getCosmeticDeliveries = $getCosmeticDeliveries->get()->toArray();
            if(!empty($getCosmeticDeliveries)){
                foreach($getCosmeticDeliveries as $key1=>$getCosmeticDeliveriesRow){
                    $solution_bottle_pack = json_decode($getCosmeticDeliveriesRow['solution_bottle_pack']);
                    $solution_1 = json_decode($getCosmeticDeliveriesRow['solution_1']);
                    $solution_2 = json_decode($getCosmeticDeliveriesRow['solution_2']);
                    $solution_3 = json_decode($getCosmeticDeliveriesRow['solution_3']);
                    $solution_4 = json_decode($getCosmeticDeliveriesRow['solution_4']);
                    $cosmetic_fresh_pack = json_decode($getCosmeticDeliveriesRow['cosmetic_fresh_pack']);
                    $cosmetic_bright_pack = json_decode($getCosmeticDeliveriesRow['cosmetic_bright_pack']);
                    $booster_packs = json_decode($getCosmeticDeliveriesRow['booster_packs']);
                    $aquaB_tips = json_decode($getCosmeticDeliveriesRow['aquaB_tips']);
                    $getCosmeticDeliveriesRow['solution_bottle_pack'] = $solution_bottle_pack->order_value ? $solution_bottle_pack->order_value : '0';
                    $getCosmeticDeliveriesRow['solution_1'] = $solution_1->order_value ? $solution_1->order_value  : '0';
                    $getCosmeticDeliveriesRow['solution_2'] = $solution_2->order_value ? $solution_2->order_value : '0';
                    $getCosmeticDeliveriesRow['solution_3'] = $solution_3->order_value ? $solution_3->order_value : '0';
                    $getCosmeticDeliveriesRow['solution_4'] = $solution_4->order_value ? $solution_4->order_value : '0';
                    $getCosmeticDeliveriesRow['cosmetic_fresh_pack'] = $cosmetic_fresh_pack->order_value ? $cosmetic_fresh_pack->order_value : '0';
                    $getCosmeticDeliveriesRow['cosmetic_bright_pack'] = $cosmetic_bright_pack->order_value ? $cosmetic_bright_pack->order_value : '0';
                    $getCosmeticDeliveriesRow['booster_packs'] = $booster_packs->order_value ? $booster_packs->order_value : '0';
                    $getCosmeticDeliveriesRow['aquaB_tips'] = $aquaB_tips->order_value ? $aquaB_tips->order_value : '0';
                    $getCosmeticDeliveriesRow['delivery_date'] = date('d-m-Y',strtotime($getCosmeticDeliveriesRow['delivery_date']));
                    $getCosmeticDeliveries[$key1] = $getCosmeticDeliveriesRow;
                }
            }
        }
        return $getCosmeticDeliveries;
    }

    static function getCosmeticListDetails($getCosmeticDataId){
        $getCosmeticData = CosmeticDeliveries::where('id',$getCosmeticDataId)->first();
        return $getCosmeticData;
    }

}
