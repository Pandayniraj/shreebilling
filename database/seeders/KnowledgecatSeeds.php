<?php

namespace Database\Seeders;

use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KnowledgecatSeeds extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 20) as $index) {
            DB::table('knowledge_cat')->insert([
                'org_id'=>'1',
                'name'=>$faker->jobTitle(),
            ]);
        }
    }
}
