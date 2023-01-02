<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
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
            DB::table('tasks')->insert(
            [
                'lead_id'			=>  Lead::all()->random()->id,
                'org_id' 			=> '1',
                'task_type' 		=> $faker->randomElement(['meeting', 'outbound', 'appointment', 'vacation', 'todo', 'inbound']),

                'location' 			=> $faker->streetAddress,
                'task_subject'		=> $faker->realText($maxNbChars = 90),
                'task_detail' 		=> $faker->realText(),
                'task_status' 		=> $faker->randomElement(['Started', 'Open', 'Processing', 'Completed']),
                'task_owner' 		=> User::all()->random()->id,
                'task_assign_to' 	=> User::all()->random()->id,
                'task_priority' 	=> $faker->randomElement(['Low', 'Medium', 'High']),
                'task_start_date' 	=> $faker->dateTime(),
                'task_due_date' 	=> $faker->dateTime(),
                'task_complete_percent' => $faker->randomNumber(2),
                'task_alert' 		=> $faker->randomElement(['0', '1']),
                'viewed' 			=> $faker->randomElement(['1', '0']),

            ]);
        }
    }
}
