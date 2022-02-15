<?php
/**
 * @var $faker \Faker\Generator
 */

return [
    'name' => $faker->unique()->city,
    'lat' => $faker->unique()->randomFloat(10, 42, 69),
    'long' => $faker->unique()->randomFloat(10, 19, 177),
];
