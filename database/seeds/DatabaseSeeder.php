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
        //Model::unguard(); // Disable mass assignment
        $this->call(AdminsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(DistrictsTableSeeder::class);
        $this->call(ZonesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(TypeingredientsTableSeeder::class);

       // Model::reguard(); // Enable mass assignment
    }
}
