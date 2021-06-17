<?php

use Illuminate\Database\Seeder;
use App\ErrorCode;

class ErrorCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $errorCode = [
            array('code'=>'W11','value'=>'10% remaining liquid in bottle 1'),
            array('code'=>'W21','value'=>'10% remaining liquid in bottle 2'),
            array('code'=>'W31','value'=>'10% remaining liquid in bottle 3'),
            array('code'=>'W41','value'=>'10% remaining liquid in bottle 4'),
            array('code'=>'W10','value'=>'0% remaining liquid in bottle 1'),
            array('code'=>'W20','value'=>'0% remaining liquid in bottle 2'),
            array('code'=>'W30','value'=>'0% remaining liquid in bottle 3'),
            array('code'=>'W40','value'=>'0% remaining liquid in bottle 4'),
            array('code'=>'W22','value'=>'Wrong bottle in receptacle 2'),
            array('code'=>'W32','value'=>'Wrong bottle in receptacle 3'),
            array('code'=>'W42','value'=>'Wrong bottle in receptacle 4'),
            array('code'=>'W13','value'=>'RFID tag indicates 0% level in bottle 1'),
            array('code'=>'W23','value'=>'RFID tag indicates 0% level in bottle 2'),
            array('code'=>'W33','value'=>'RFID tag indicates 0% level in bottle 3'),
            array('code'=>'W43','value'=>'RFID tag indicates 0% level in bottle 4'),
            array('code'=>'W14','value'=>'Liquid level – Floater report nn ml in excess of RFID for Bottle 1'),
            array('code'=>'W24','value'=>'Liquid level - Floater report nn ml in excess of RFID for Bottle 2'),
            array('code'=>'W34','value'=>'Liquid level - Floater report nn ml in excess of RFID for Bottle 3'),
            array('code'=>'W44','value'=>'Liquid level - Floater report nn ml in excess of RFID for Bottle 4'),
            array('code'=>'W50','value'=>'Waste bottle full'),
            array('code'=>'W51','value'=>'Suction error (vacuum)'),
            array('code'=>'W52','value'=>'Bottle changed'),
            array('code'=>'E01','value'=>'Unable to read UTC time'),
            array('code'=>'E02','value'=>'Unable to read or save the service'),
            array('code'=>'E03','value'=>'Floater Controller not present'),
            array('code'=>'E05','value'=>'VibroX not present'),
            array('code'=>'E06','value'=>'MicroT not present'),
            array('code'=>'E07','value'=>'Collagen+ not present'),
            array('code'=>'E08','value'=>'UltraB not present'),
            array('code'=>'E09','value'=>'RFID1 not present'),
            array('code'=>'E10','value'=>'RFID2 not present'),
            array('code'=>'E11','value'=>'RFID3 not present'),
            array('code'=>'E12','value'=>'RFID4 not present'),
            array('code'=>'E13','value'=>'Modem not present'),
            array('code'=>'E14','value'=>'Unable to read the user configuration'),
            array('code'=>'E15','value'=>'Unable to read the time counters'),
            array('code'=>'E16','value'=>'Current Active handset has stopped responding'),


         ];
         foreach ($errorCode as $error) {
            ErrorCode::firstOrCreate([
                'code' => $error['code'],
                'value' => $error['value'],
            ]);
        }
    }
}
