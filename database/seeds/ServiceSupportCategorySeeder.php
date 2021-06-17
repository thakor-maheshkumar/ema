<?php

use Illuminate\Database\Seeder;
use App\ServiceSupportCategory;

class ServiceSupportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryData = array(
            'User Manuals by Device Type',
            'Service Manuals by Device Typ',
            'General Demonstration Videos by Device Type',
            'How to Demonstration videos – Treatment by Device Type',
            'How to Demonstration videos – Technical by Device Type',
            'Recommended Treatment Protocols by Device Type',
            'Cosmetics and Solution Information by Device Type'
        );
        foreach($categoryData as $categoryRow){
            ServiceSupportCategory::firstOrCreate(['category_name'=>$categoryRow]);
        }
    }
}
