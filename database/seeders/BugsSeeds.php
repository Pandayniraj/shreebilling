<?php

namespace Database\Seeders;

use App\Models\Projects;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BugsSeeds extends Seeder
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
            DB::table('bugs')->insert(
                [
                    'org_id'=>'1',
                    'project_id'=>Projects::all()->random()->id,
                    'user_id'=>User::all()->random()->id,
                    'assigned_to'=>User::all()->random()->id,
                    'category'=>$faker->jobTitle(),
                    'description'=>$faker->realText(),
                    'priority'=>$faker->randomElement(['l', 'h', 'm']),
                    'source'=>$faker->randomElement(['internal', 'email', 'cases', 'client']),
                    'status'=>$faker->randomElement(['open', 'in_progress', 'assigned', 'to_be_fixed', 'reopen', 'fixed']),
                    'subject'=>$faker->realText(),
                    'type' =>$faker->randomElement(['defect', 'feature']),
                    'viewed' =>$faker->randomElement(['0', '1']),
                    'enabled' =>'1',
                ]
            );
        }
    }
}
