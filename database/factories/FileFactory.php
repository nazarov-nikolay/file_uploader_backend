<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\File::class, function (Faker $faker) {
    $email = $faker->unique()->safeEmail;
    $md5 = $faker->md5;
    $salt = Config::get('app.salt');

    $email_md5 = md5( $salt . $email);
    $file_md5 = md5( $salt . $md5);
    $url = $email_md5 . '/' . $file_md5;

    $date = new \DateTime();

    return [
        'email' => $email,
        'name' => $faker->domainWord . '.' .$faker->fileExtension,
        'description' => $faker->text,
        'md5' => $md5,
        'size' => $faker->numberBetween(104857600, 157286400),
        'type' => $faker->mimeType,
        'status' => 'completed',
        'last_update' => $date->getTimestamp() . '000',
        'url' => $url
    ];
});
