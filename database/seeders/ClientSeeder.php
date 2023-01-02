<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 200) as $index) {
            DB::table('clients')->insert(
            [
                'org_id' => '1',
                'name' => $faker->company,
                'location' => $faker->streetAddress,
                'phone' => $faker->phoneNumber,
                'vat' => $faker->randomNumber(8),
                'email' => $faker->email,
                'website' => $faker->url,
                'industry' => $faker->randomElement(['Hospitality', 'Travel', 'Education', 'Health', 'IT']),
                'type' => $faker->randomElement(['Competitor', 'Customer', 'Distributor', 'Investor', 'Partner', 'Supplier']),

            ]);
        }
    }
}
