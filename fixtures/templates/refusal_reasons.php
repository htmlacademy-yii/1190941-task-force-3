<?php
/**
 * @var $faker \Faker\Generator
 */

return [
    'comment' => $faker->sentences(3, true),
    'task_id' => $faker->numberBetween(1, 100),
    'user_id' => $faker->numberBetween(1, 20),
];
