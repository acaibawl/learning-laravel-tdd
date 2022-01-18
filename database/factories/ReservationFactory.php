<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Lesson;
use App\Models\Reservation;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Reservation::class, function (Faker $faker) {
    return [
        // ユーザ・レッスンは何でもよいから予約データを何件か作りたいという作り方をデフォルトに登録
        'lesson_id' => function() {
            return factory(Lesson::class)->create()->id;
        },
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
    ];
});
