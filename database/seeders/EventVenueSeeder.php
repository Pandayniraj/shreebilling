<?php

namespace Database\Seeders;

use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventVenueSeeder extends Seeder
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
            DB::table('event_venues')->insert([
                'venue_name' => $faker->state(),
                'venue_facilities' => $faker->realText(),
                'other_details' =>$faker->realText(),
                'user_id' =>User::all()->random()->id,
                ]
            );
        }
    }
}
