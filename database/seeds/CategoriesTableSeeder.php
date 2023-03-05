<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Database seeds only for 1 record categories table.
        Category::create([
            'category_name' => 'Root',
            'description' => 'This is the root category, don\'t delete this',       
            'parent_id' => null,
            'menu' => 0,            
            ]);
        // using factory we are creating 7 more records to categories table.
        // so we need to initiate CategoryFactory now() for database seeding.   
        factory(App\Models\Category::class, 7)->create();    
    }
}