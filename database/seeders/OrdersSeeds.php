<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Orders;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeds extends Seeder
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
            $last_order = Orders::orderBy('order_id', 'Desc')->first();
            DB::table('fin_orders')->insert([
                 'order_id' => $last_order->order_id + 1,
                 'user_id' =>  User::all()->random()->id,
                 'client_id' =>  Lead::all()->random()->id,
                 'org_id' => '1',
                 'order_type' => $faker->randomElement(['quotation', 'proforma_invoice', 'order']),
                 'name' => $faker->firstName.' '.$faker->lastName,
                 'position' => $faker->jobTitle,
                 'address' =>$faker->address(),
                 'status' =>'Active',
                 'bill_date'=>$faker->dateTime(),
                 'due_date'=>$faker->dateTimeInInterval($startDate = 'now', $interval = '+ 5 days', $timezone = null),
                 'amount' => $faker->randomNumber(),
                 'taxable_amount' =>$faker->randomNumber(),
                 'tax_amount' => $faker->randomNumber(),
                 'total_amount' => $faker->randomNumber(),
                 'subtotal' => $faker->randomNumber(),
                 'terms' =>'custom',

             ]);
        }
    }
}
