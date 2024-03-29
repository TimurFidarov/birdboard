<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence(4),
        'project_id' => factory('App\Project'),
        'completed' => false,
    ];
});
