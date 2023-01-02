<?php

namespace Database\Seeders;

use App\Models\Projectscat;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsSeeder extends Seeder
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
            DB::table('projects')->insert([
                'projects_cat' => Projectscat::all()->random()->id,
                'class'=>$faker->randomElement(['label-primary', 'label-warning', 'label-sucess', 'label-default']),
                'name' => $faker->company,
                'staffs' => User::all()->random()->id,
                'org_id'=>'1',
                'assign_to' => User::all()->random()->id,
                'description'=>$faker->realText(),
                'start_date'=>$faker->dateTime(),
                'end_date' => $faker->dateTimeInInterval($startDate = 'now', $interval = '+ 5 days', $timezone = null),
                'status' => $faker->randomElement(['Completed', 'Started', 'New']),
                'enabled' =>'1',
                'created_at' => $faker->dateTime(),
            ]);
        }
        //
    }
}
