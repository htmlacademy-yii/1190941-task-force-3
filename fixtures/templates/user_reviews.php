<?php
/**
 * @var $faker \Faker\Generator
 */

return [
    'review' => $faker->sentences(4, true),
    'rating' => $faker->numberBetween(0, 5),
    'created_at' => $faker->iso8601(),
    'reviewer_id' => $faker->numberBetween(0, 20),
    'task_id' => $faker->numberBetween(0, 100),
];
