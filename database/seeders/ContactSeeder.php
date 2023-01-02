<?php

namespace Database\Seeders;

use App\Models\Client;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            DB::table('contacts')->insert(
            [
                'full_name'=>$faker->firstName.' '.$faker->lastName,
                'org_id' => '1',
                'client_id' => Client::all()->random()->id,
                'position' => $faker->randomElement(['Marketing Director', 'DOSM', 'IT Manager']),
                'email_1' => $faker->email,
                'phone' => $faker->phoneNumber,
            ]);
        }
    }
}
