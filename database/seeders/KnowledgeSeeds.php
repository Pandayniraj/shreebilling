<?php

namespace Database\Seeders;

use App\Models\Knowledgecat;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KnowledgeSeeds extends Seeder
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
            DB::table('knowledge')->insert([
                'author_id'=>User::all()->random()->id,
                'org_id'=>'1',
                'title'=>$faker->realText($maxNbChars = 90, $indexSize = 2),
                'cat_id'=>Knowledgecat::all()->random()->id,
                'description'=>$faker->realText(),
                'body'=>$faker->text(),
                'expire_at'=>$faker->dateTime(),
            ]);
        }
    }
}
