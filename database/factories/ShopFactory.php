<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Shop;
use App\Models\Prefecture;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Shop::class, function (Faker $faker) {

    $name = ['セブンイレブン','ファミリーマート', 'ローソン', 'ミニストップ'];
    $branchName = ['山田支店', '田中本店', '佐藤支店', '柳岡本店', '山㟢支店', '島田支店'];
    $buildingName = ['山田マンション', '田中デパート', '佐藤マンション', '山中デパート', '柳田マンション'];
    /* 都道府県テーブルに紐付けるIDを取得する*/
    $prefectureIDs  = Prefecture::pluck('id')->all();


    return [
        'name' => $faker->randomElement($name),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => \Hash::make('123abc'),
        'remember_token' => Str::random(10),
        'branch_name' => $faker->randomElement($branchName),
        'prefecture_id' => $faker->randomElement($prefectureIDs),
        'city' => $faker->streetAddress,
        'other_address' => $faker->randomElement($buildingName) . '1F',
        'profile' => $faker->realText($maxNbChars = 100, $indexSize = 2),
    ];
});
