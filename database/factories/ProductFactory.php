<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    $image = $faker->randomElement([
        "https://acg.yanwz.cn/api.php",
        "http://img.xjh.me/random_img.php",
        "https://source.unsplash.com/random",
        "https://picsum.photos/200/300/?random",
        "https://source.unsplash.com/user/erondu",
        "https://acg.toubiec.cn/random.php",
    ]);
    return [
        'title'        => $faker->word,
        'description'  => $faker->sentence,
        'image'        => $image,
        'on_sale'      => true,
        'rating'       => $faker->numberBetween(0, 5),
        'sold_count'   => 0,
        'review_count' => 0,
        'price'        => 0,
    ];
});
