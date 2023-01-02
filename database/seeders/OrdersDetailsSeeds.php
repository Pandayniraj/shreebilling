<?php

namespace Database\Seeders;

use App\Models\Orders;
use App\Models\Product;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersDetailsSeeds extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            DB::table('fin_order_detail')->insert([
                'client_id' => User::all()->random()->id,
                'order_id' => Orders::all()->random()->id,
                'product_id' => Product::all()->random()->id,
                'description' => $faker->realText(),
                'price' => $faker->randomNumber(),
                'quantity' => $faker->numberBetween($min = 1, $max = 100),
                'total' =>$faker->randomNumber(),
                'date' => $faker->dateTime(),
                'is_inventory' => $faker->randomElement(['1', '0']),
            ]);
        }
    }
}
