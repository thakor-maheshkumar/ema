<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'ema users',
            'ema users create',
            'ema users read',
            'ema users update',
            'ema users delete',
            'ema users suspend',
            'ema users release',
            'ema users forcelogout',
            'ema users resetpassword',
            'distributor',
            'distributor create',
            'distributor read',
            'distributor update',
            'distributor delete',
            'distributor suspend',
            'distributor users',
            'distributor users create',
            'distributor users read',
            'distributor users update',
            'distributor users delete',
            'distributor users suspend',
            'distributor users release',
            'distributor users forcelogout',
            'distributor users resetpassword',
            'treatmentcentre',
            'treatmentcentre create',
            'treatmentcentre read',
            'treatmentcentre update',
            'treatmentcentre delete',
            'treatmentcentre suspend',
            'treatmentcentre users',
            'treatmentcentre users create',
            'treatmentcentre users read',
            'treatmentcentre users update',
            'treatmentcentre users delete',
            'treatmentcentre users suspend',
            'treatmentcentre users release',
            'treatmentcentre users forcelogout',
            'treatmentcentre users resetpassword',
            'hydracoolsrp',
            'hydracoolsrp create',
            'hydracoolsrp read',
            'hydracoolsrp update',
            'hydracoolsrp delete',
            'cosmeticdata',
            'cosmeticdata create',
            'cosmeticdata read',
            'cosmeticdata update',
            'cosmeticdata delete',
            'media library',
            'media library create',
            'media library download',
            'media library delete',
            'treatmentdata',
            'treatmentdata upload',
            'audit log',
            'audit log read',
            'report',
            'diagnostic'
         ];

         foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
         }
    }
}
