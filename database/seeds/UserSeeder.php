<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //System Administrator
        $role = Role::find(1);
        $permissions = Permission::all();
        $role->syncPermissions($permissions);

        //EMA Analysts
        $role = Role::find(2);
        $permissions = Permission::find([1,10,25,40,54,59,50,58,12,27,3,18,33,55,42,47]);
        $role->syncPermissions($permissions);

        //EMA Service Support
        $role = Role::find(3);
        $permissions = Permission::find([1,10,25,40,56,59,50,58,12,27,3,6,7,8,9,18,21,22,23,24,33,36,37,38,39,41,42,43,44,47]);
        $role->syncPermissions($permissions);


        // Treatment Centre Manager
        $role = Role::find(5);
        $permissions = Permission::find([25,40,54,59,50,58,27,28,31,32,33,34,35,36,37,38,39,55,42]);
        $role->syncPermissions($permissions);


        // Distributor Sales
        $role = Role::find(7);
        $permissions = Permission::find([10,25,40,54,59,50,58,12,27,28,16,18,31,33,38,39,55,42,45,46,47,48,49]);
        $role->syncPermissions($permissions);

        // Distributor Service
        $role = Role::find(8);
        $permissions = Permission::find([10,25,40,59,50,58,12,27,28,16,18,31,33,36,37,38,39,42,45,46,47,48,49]);
        $role->syncPermissions($permissions);

        // Distributor Principal
        $role = Role::find(9);
        $permissions = Permission::find([10,25,40,54,56,59,50,58,12,13,26,27,28,16,17,18,19,20,21,22,23,24,31,32,33,34,35,36,37,38,39,55,42,45,46,47,48,49]);
        $role->syncPermissions($permissions);

        $user = Factory(App\User::class)->create([
            'name' => 'System Admin',
            'username' => 'system_admin',
            'email' => 'superadmin@dispostable.com',
            'password'=>Hash::make('Annet!2020'),
            'internal_id'=>'00001',
            'primary_telephone_number'=>'12345679',
            'mobile_telephone_number'=>'123456789'
        ]);
        $user->assignRole('system administrator');

    }
}
