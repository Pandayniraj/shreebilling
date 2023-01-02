<?php

namespace Database\Seeders;

use App\Models\Event;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class EventSpaceSeeder extends Seeder
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
            DB::table('event_space')->insert([
                'event_id' => Event::all()->random()->id,
                'room_name' => $faker->word(),
                'room_capability' =>$faker->randomDigit(),
                'daily_rate' =>$faker->randomNumber(),
                'occupied_date_from' =>$faker->dateTime(),
                 'occupied_date_to'=>$faker->dateTimeInInterval($startDate = 'now', $interval = '+ 5 days', $timezone = null),
                 'booking_status' =>$faker->randomElement(['confirmed', 'provisional', '', '']),
                 'other_details'=>$faker->realText(),
                 'user_id'=>User::all()->random()->id,
            ]);
        }
    }
}
