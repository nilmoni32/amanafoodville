<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use Faker\Generator as Faker;

// defining factory for category for 7 more records.
$factory->define(Category::class, function (Faker $faker) {
    return [
        'category_name' => $faker->name,
        'description'   => $faker->realText(100),
        'parent_id'     => 1,
        'menu'          => 1,
    ];
});
