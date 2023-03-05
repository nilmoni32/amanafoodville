<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
        //Role::truncate();
        // define roles as seeds data for roles table.
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'order-control']);
        Role::create(['name' => 'stock-control']);
        Role::create(['name' => 'user']); // generic user for reporting only.
        Role::create(['name' => 'super-admin']); 
    }
}
