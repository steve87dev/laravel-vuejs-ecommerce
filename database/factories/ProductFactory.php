<?php

use Faker\Generator as Faker;

$factory->define(\App\Product::class, function (Faker $faker) {
    static $number =0;
    return [
        'product_title'=>$faker->text(50),
        'product_description'=>$faker->text(200),
        'image'=>'image_'.$number++.'.jpg',
        'price'=>$faker->numberBetween(10,1000),
        'status'=>'instock'
    ];
});
