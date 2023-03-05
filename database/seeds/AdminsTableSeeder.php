<?php

use App\Models\Admin;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // truncate() will remove all rows and reset the auto-incrementing ID to zero
        //Admin::truncate();
       // DB::table('role_user')::truncate();
        
        // creating database seeds for admins table.
        $admin = Admin::create([
        'name' => 'Nilmoni Mustafi',
        'email' => 'mustafi.amana@gmail.com',       
        'password' => bcrypt('12345678'), 
        ]);
        $orderController  = Admin::create([
        'name' => 'Rathin Paul',
        'email' => 'rathin.paul@gmail.com',       
        'password' => bcrypt('12345678'), 
        ]);
        $user = Admin::create([
        'name' => 'Generic User',
        'email' => 'user@gmail.com',       
        'password' => bcrypt('12345678'), 
        ]);
        
        // getting the roles from Role table.
        $adminRole = Role::where('name', 'admin')->first();
        $orderControllerRole = Role::where('name', 'order_controller')->first();
        $userRole = Role::where('name', 'user')->first();

        //attaching roles to admin users in the intermediate table [admin_role] using attach method.       
        $admin->roles()->attach($adminRole);
        $orderController->roles()->attach($orderControllerRole);
        $user->roles()->attach($userRole);
    }
}
