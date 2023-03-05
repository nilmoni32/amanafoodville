<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Admin;
use Faker\Generator as Faker;

$factory->define(Admin::class, function (Faker $faker) {
    return [        
        'name' => $faker->name,
        'email' => $faker->unique()->email,       
        'password' => bcrypt('password'),
    ];
});
