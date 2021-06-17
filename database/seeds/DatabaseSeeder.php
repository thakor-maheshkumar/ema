<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PrimaryRegionSeeder::class);
        $this->call(SecondaryRegionSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(ServiceSupportCategorySeeder::class);
        $this->call(CoreSettingsSeeder::class);
        $this->call(EmailTemplateSeeder::class);
        $this->call(ErrorCodeSeeder::class);

    }
}
