<?php

use App\SecondaryRegion;
use Illuminate\Database\Seeder;

class SecondaryRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $secondaryRegion = [
            array('name'=>'North America','primary_region_id'=>'1'),
            array('name'=>'East Asia','primary_region_id'=>'2'),
            array('name'=>'North Asia','primary_region_id'=>'2'),
            array('name'=>'Pacific','primary_region_id'=>'2'),
            array('name'=>'South Asia','primary_region_id'=>'2'),
            array('name'=>'South East Asia','primary_region_id'=>'2'),
            array('name'=>'East Europe','primary_region_id'=>'3'),
            array('name'=>'Europe','primary_region_id'=>'3'),
            array('name'=>'West Europe','primary_region_id'=>'3'),
            array('name'=>'Africa','primary_region_id'=>'4'),
            array('name'=>'Middle East','primary_region_id'=>'4'),
            array('name'=>'North Africa','primary_region_id'=>'4'),
            array('name'=>'Pacific','primary_region_id'=>'4'),
            array('name'=>'South America','primary_region_id'=>'4')
        ];
        foreach ($secondaryRegion as $secondayRegionRow) {
            SecondaryRegion::firstOrCreate([
                'name' => $secondayRegionRow['name'],
                'fk_primary_region_id' => $secondayRegionRow['primary_region_id']
            ]);
        }
    }
}
