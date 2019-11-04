<?php

/** @var $factory Illuminate\Database\Eloquent\Factory */
$factory->define(\RainLab\User\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name'                  => $faker->name,
        'surname'               => $faker->lastName,
        'email'                 => $email = $faker->safeEmail,
        'password'              => $password = $faker->password(8),
        'password_confirmation' => $password,
        'is_activated'          => true,
        'activated_at'          => now(),
        'username'              => $email,
        'is_guest'              => false,
        'is_superuser'          => false,
    ];
});
