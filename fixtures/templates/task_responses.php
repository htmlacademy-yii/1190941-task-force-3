<?php
/**
 * @var $faker \Faker\Generator
 */

return [
    'comment' => $faker->sentences(3, true),
    'created_at' => $faker->date() . ' ' . $faker->time(),
    'price' => $faker->numberBetween(1000, 100000),
    'task_id' => $faker->numberBetween(1, 100),
    'user_id' => $faker->numberBetween(1, 20),
];
