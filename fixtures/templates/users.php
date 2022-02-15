<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'status' => $faker->numberBetween(0, 1),
    'name' => $faker->name,
    'email' => $faker->unique()->email,
    'password' => Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
    'created_at' => $faker->date() . ' ' . $faker->time(),
    'last_action_time' => $faker->date() . ' ' . $faker->time(),
    'avatar_name' => $faker->unique()->image('web/img/avatars/', 55, 55, null, false),
    'date_of_birth' => $faker->date() . ' ' . $faker->time(),
    'phone' => substr($faker->unique()->e164PhoneNumber, 1, 11),
    'telegram' => $faker->unique()->userName,
    'about' => $faker->sentences(4, true),
    'role_id' => $faker->numberBetween(0, 1),
    'city_id' => $faker->numberBetween(1, 1000),
];
