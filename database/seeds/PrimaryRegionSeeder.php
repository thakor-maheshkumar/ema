<?php

use App\PrimaryRegion;
use Illuminate\Database\Seeder;

class PrimaryRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $primaryRegion = [
            'Americas',
            'Asia',
            'Europe',
            'Rest Of World',
        ];
        foreach ($primaryRegion as $primaryRegionRow) {
            PrimaryRegion::firstOrCreate([
                'name' => $primaryRegionRow,
            ]);
        }
    }
}
