<?php

use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // creating database seeds for admins table.  

        $districts = [            
            ['name' => 'Dhaka'],
            ['name' => 'Rajshahi'],
            ['name' => 'Natore'],
            ['name' => 'Cumilla'],
            ['name' => 'Chattogram'],
            ['name' => 'Khulna'],
            ['name' => 'Bogura'],
            ['name' => 'Sirajganj'],
        ];

        foreach ($districts as $district) {
            District::create($district);
        }
    }
}
