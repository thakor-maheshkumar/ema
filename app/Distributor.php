<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Distributor extends Model
{
    protected $table='distributors';

    public function country(){
    	    return $this->belongsTo('App\Country');
    }
    public function distributor_user()
    {
        return $this->hasOne('App\DistributorUser','fk_distributor_id','id');
    }
    public function distributor_users()
    {
        return $this->hasMany('App\DistributorUser','fk_distributor_id','id');
    }

    /* Get distributor list */
    static function getDistributorListData($searchData=null){
        $authUser = Auth::user();
        $distributor=   DB::table('distributors as d')
                        ->select('d.*','dum.id as distributor_user', DB::raw("(SELECT pr.name  FROM country c INNER JOIN secondary_region sr ON sr.id = c.fk_secondary_region_id INNER JOIN primary_region pr ON pr.id = sr.fk_primary_region_id WHERE c.id = d.country_id) AS primary_region,(SELECT Count('id') FROM   treatment_center WHERE  status IN( 1, 3 ) AND distributors = d.id) AS total_treatmentcetre, (SELECT Count(h.id) FROM   distributors dis INNER JOIN treatment_center t ON t.distributors = dis.id INNER JOIN hydracool_srp h ON h.fk_treatment_centers_id = t.id WHERE  dis.id = d.id AND t.status IN( 1, 3 ) AND dis.status IN( 1, 3 ) AND h.status IN( 1, 3 ) ) AS total_install_device"))
                        ->leftjoin('distributor_user_mapping as dum','dum.fk_distributor_id','=','d.id')
                        ->whereIn('d.status',[1,3]);
                        if($authUser->hasRole(['distributor principal']) || $authUser->hasRole(['distributor service']) || $authUser->hasRole(['distributor sales'])){
                            $getDistributorCompanyData = getDistributorCompanyDataById();
                            $distributor->where('d.id',$getDistributorCompanyData->distributor_company_id);
                        }
                        if(!empty($searchData)){
                            foreach($searchData as $key=>$val){
                                if($key=="total_treatmentcetre" || $key=="total_install_device"){
                                    if($val){
                                        $distributor->having($key,$val);
                                    }
                                }else{
                                    if($val && $key!="primary_region"){
                                        $distributor->where('d.'.$key,'LIKE',"%".$val."%");
                                    }
                                }
                            }
                        }
            $distributor->groupBy('id');
            if(!empty($searchData)){
                foreach($searchData as $key=>$val){
                    if($key=="primary_region"){
                        $distributor->having($key,'LIKE','%'.$val.'%');
                    }
                }
            }
            $distributor->orderBy('d.id','DESC');
            if(!empty($searchData)){
                $distributor = $distributor->get();
                $distributor = $distributor->map(function ($item, $key) {
                    return (array) $item;
                })
                ->all();
            }

        return $distributor;
    }

}
