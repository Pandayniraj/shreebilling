<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Product;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProposalSeeder extends Seeder
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
            DB::table('proposals')->insert(
            [
                'client_lead_id'	=>  Lead::all()->random()->id,
                'contact_id'       =>  Lead::all()->random()->id,
                'org_id' 			=> '1',
                'client_type'       => 'lead',
                'type' 				=> $faker->randomElement(['proposal', 'contract']),
                'product_id'		=> Product::all()->random()->id,
                'subject'			=> $faker->realText($maxNbChars = 50),
                'body' 				=> $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'status' 			=> $faker->randomElement(['draft', 'paused', 'negotiating', 'finalized', 'sold']),
                'user_id' 			=> User::all()->random()->id,
                'created_at' 		=> $faker->dateTime(),
            ]);
        }
    }
}
