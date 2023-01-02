<?php

namespace Database\Seeders;

use App\Models\Projects;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectUserSeeder extends Seeder
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
            DB::table('project_users')->insert(
            [
                'project_id' 	=> Projects::all()->random()->id,
                'user_id' 		=> User::all()->random()->id,

            ]);
        }
    }
}
