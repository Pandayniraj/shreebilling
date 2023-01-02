<?php

namespace Database\Seeders;

use App\Models\Projects;
use App\Models\ProjectTaskCategory;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTasks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 600) as $index) {
            DB::table('project_tasks')->insert([
                 'project_id'     => Projects::all()->random()->id,
                 'user_id'        => User::all()->random()->id,
                 'subject'        => $faker->realText($maxNbChars = 40),
                 'description'    =>$faker->realText(),
                 'actual_duration'=>$faker->randomDigit.'d',
                 'duration'       =>$faker->randomDigit.'d',
                 'estimated_effort'=>$faker->randomDigit.'d',
                 'priority'       =>$faker->randomElement(['High', 'Medium', 'Low']),
                 'peoples'        =>User::all()->random()->id,
                 'start_date'     =>$faker->dateTime(),
                'end_date'       => $faker->dateTimeInInterval($startDate = 'now', $interval = '+ 5 days', $timezone = null),
                'status'         => $faker->randomElement(['Completed', 'Started', 'New']),
                'category_id'        =>ProjectTaskCategory::all()->random()->id,
             ]);
        }
    }
}
