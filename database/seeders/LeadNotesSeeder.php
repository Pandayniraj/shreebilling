<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 500) as $index) {
            DB::table('lead_notes')->insert(
            [
                'lead_id'			=>  Lead::all()->random()->id,
                'user_id' 			=> User::all()->random()->id,
                'note' 				=> $faker->realText(),
                'created_at' 		=> $faker->dateTimeBetween('2010-01-01', '2019-10-31'),
            ]);
        }
    }
}
