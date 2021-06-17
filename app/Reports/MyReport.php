<?php


namespace App\Reports;
use App\TreatmentCenter;
use App\DynamoDbModel;

class MyReport extends \koolreport\KoolReport
{
    use \koolreport\laravel\Friendship;

    function settings()
    {
        return array(
            "dataSources"=>array(
                "elo"=>array(
                    "class"=>'\koolreport\laravel\Eloquent', // This is important
                )
            )
        );
    }

    // function setup()
    // {
    //     // Let say, you have "sale_database" is defined in Laravel's database settings.
    //     // Now you can use that database without any futher setitngs.
    //     $this->src("mysql")
    //     ->query("SELECT internal_id,full_company_name FROM treatment_center")
    //     ->pipe($this->dataStore("treatment_center"));
    // }

    function setup()
    {
        //Now you can use Eloquent inside query() like you normally do
        $this->src("elo")->query(
          DynamoDbModel::orderBy('pid','desc')
        )
        ->pipe($this->dataStore("treatment_center"));
    }
}