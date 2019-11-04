<?php

/** @var $factory Illuminate\Database\Eloquent\Factory */

use Backend\Models\User;
use Backend\Models\UserRole;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'first_name'            => $faker->firstName,
        'last_name'             => $faker->lastName,
        'login'                 => $faker->userName,
        'email'                 => $faker->safeEmail,
        'password'              => $password = $faker->password(8),
        'password_confirmation' => $password,
        'is_activated'          => true,
        'activated_at'          => now(),
        'role_id'               => null,
        'is_superuser'          => false,
    ];
});

$factory->state(User::class, 'superuser', [
    'is_superuser' => true,
]);

$factory->state(User::class, 'role:publisher', function () {
    $role = UserRole::where('code', 'publisher')->first();

    return ['role_id' => $role->id ?? null];
});

$factory->state(User::class, 'role:developer', function () {
    $role = UserRole::where('code', 'developer')->first();

    return ['role_id' => $role->id ?? null];
});
