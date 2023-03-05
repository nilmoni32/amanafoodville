<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Typeingredient;
use Faker\Generator as Faker;

$factory->define(Typeingredient::class, function (Faker $faker) {    
    return [
        'name' => $faker->name,
        'description'   => $faker->realText(100),
        'parent_id'     => 1,       
    ];    
});
