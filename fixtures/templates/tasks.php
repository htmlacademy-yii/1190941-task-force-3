<?php
/**
 * @var $faker \Faker\Generator
 */

return [
    'status' => $faker->numberBetween(0, 4),
    'created_at' => $faker->date() . ' ' . $faker->time(),
    'title' => $faker->unique()->text(255),
    'description' => $faker->paragraphs(3, true),
    'lat' => $faker->unique()->randomFloat(10, 42, 69),
    'long' => $faker->unique()->randomFloat(10, 19, 177),
    'price' => $faker->numberBetween(1000, 100000),
    'deadline' => $faker->date() . ' ' . $faker->time(),
    'category_id' => $faker->numberBetween(1, 20),
    'customer_id' => $faker->numberBetween(1, 10),
    'performer_id' => $faker->numberBetween(11, 20),
    'city_id' => $faker->numberBetween(1, 1000),
];
