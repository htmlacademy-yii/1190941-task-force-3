<?php
/**
 * @var $faker \Faker\Generator
 */

return [
    'review' => $faker->sentences(4, true),
    'rating' => $faker->numberBetween(0, 5),
    'created_at' => $faker->date() . ' ' . $faker->time(),
    'reviewer_id' => $faker->numberBetween(1, 20),
    'user_id' => $faker->numberBetween(1, 20),
    'task_id' => $faker->numberBetween(1, 100),
];
