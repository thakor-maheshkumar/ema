<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reports\MyReport;

class ReportController extends Controller
{
    public function reportTreatmentByCustomerNumber(){
        $report = new MyReport;
        $report->run();
        return view("reports.treatment-by-customer-number",["report"=>$report]);
    }
}
