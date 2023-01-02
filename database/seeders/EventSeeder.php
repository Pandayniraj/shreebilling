<?php

namespace Database\Seeders;

use App\Models\EventVenues;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
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
            DB::table('events')->insert(
                [
                    'event_type' => $faker->randomElement(['concert', 'dinner', 'lunch', 'hightea', 'cocktail', 'picnic', 'party', 'seminar', 'conference', 'workshop', 'galas']),
                    'venue_id' => EventVenues::all()->random()->id,
                    'event_name' =>$faker->jobTitle(),
                    'event_start_date' =>$faker->dateTime(),
                     'event_end_date'=>$faker->dateTimeInInterval($startDate = 'now', $interval = '+ 5 days', $timezone = null),
                     'event_status'=>$faker->randomElement(['registered', 'paid', 'cancelled', 'postpone']),
                     'num_participants' => $faker->randomDigit(),
                     'amount_paid'=>$faker->randomFloat($nbMaxDecimals = null, $min = 100, $max = null),
                     'potential_cost' =>$faker->randomFloat($nbMaxDecimals = null, $min = 100, $max = null),
                     'calculated_cost' => $faker->randomFloat($nbMaxDecimals = null, $min = 100, $max = null),
                     'edited_cost'=> $faker->randomFloat($nbMaxDecimals = null, $min = 100, $max = null),
                     'extra_cost'=>$faker->randomFloat($nbMaxDecimals = null, $min = 100, $max = null),
                     'comments'=>$faker->realText(),
                     'menu_items'=>$faker->realText(),
                     'other_details'=>$faker->realText(),
                     'user_id' => User::all()->random()->id,
                ]
            );
        }
    }
}
