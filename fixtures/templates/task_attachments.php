<?php
/**
 * @var $faker \Faker\Generator
 */

return [
    'attachment_path' => $faker->file('web\img', 'web\uploads', false),
    'type' => $faker->numberBetween(1, 3),
    'task_id' => $faker->numberBetween(1, 100),
];
