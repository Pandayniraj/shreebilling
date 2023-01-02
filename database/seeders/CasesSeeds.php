<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasesSeeds extends Seeder
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
            DB::table('cases')->insert([
            'user_id'          => User::all()->random()->id,
            'lead_id'          => Lead::all()->random()->id,
            'org_id'           => '1',
            'assigned_to'      => User::all()->random()->id,
            'status'           => $faker->randomElement(['new', 'assigned', 'closed', 'pending', 'rejected']),
            'type'             =>$faker->randomElement(['issue', 'question', 'ticket']),
            'subject'          =>$faker->realText($maxNbChars = 50),
            'description'      =>$faker->realText(),
            'resolution'       =>$faker->realText(),
            'viewed'           =>$faker->randomElement(['0', '1']),
            'enabled'          =>'1',
        ]);
        }
    }
}
