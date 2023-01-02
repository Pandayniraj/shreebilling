<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadQuerySeeder extends Seeder
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
            DB::table('lead_query')->insert([
                                  'product' => Product::all()->random()->id,
                                   'lead_id' => Lead::all()->random()->id,
                               'phone' => $faker->phoneNumber,
                               'email' => $faker->email,
                               'status' => $faker->randomElement(['New', 'Follow Up', 'Closed']),
                               'detail' => $faker->realText(),
                               'next_action_date' => $faker->dateTime(),
                               'price' => $faker->randomNumber,
                               'contact_person' => $faker->firstName.' '.$faker->lastName,
                           ]);
        }
    }
}
