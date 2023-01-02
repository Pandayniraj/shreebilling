<?php

namespace Database\Seeders;

use App\Models\Query;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadQueryPaxSeeder extends Seeder
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
            DB::table('lead_query_pax')->insert([
                                 'pax_name' => $faker->firstName.' '.$faker->lastName,
                                    'mileage_card' => $faker->randomNumber,
                                    'lead_query_id' => Query::all()->random()->id,
                           ]);
        }
    }
}
