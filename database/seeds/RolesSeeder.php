<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'system administrator',
            'ema analyst',
            'ema service support',
            'hydracool srp unit',
            'treatment centre manager',
            'treatment centre employee',
            'distributor sales',
            'distributor service',
            'distributor principal'
         ];
         foreach ($roles as $role) {
                Role::firstOrCreate(['name' => $role]);
         }
    }
}
