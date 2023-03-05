<?php

use Illuminate\Database\Seeder;
use App\Models\Typeingredient;

class TypeingredientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Database seeds only for 1 record categories table.
        Typeingredient::create([
            'name' => 'Root',
            'description' => 'This is the root category, don\'t delete this',       
            'parent_id' => null                      
            ]);
        // using factory we are creating 8 more records to typeingredients table.
        // so we need to initiate Factory now() for database seeding.   
        factory(App\Models\Typeingredient::class, 8)->create();    
    }
}
