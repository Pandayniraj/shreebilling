<?php

namespace Database\Seeders;

use App\Models\Communication;
use App\Models\Leadstatus;
use App\Models\Leadtype;
use App\Models\Product;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            DB::table('leads')->insert([
                                  'title' => $faker->title,
                               'name' => $faker->firstName.' '.$faker->lastName,
                               'email' => $faker->email,
                               'org_id' => '1',
                               'lead_type_id' => Leadtype::all()->random()->id,
                               'course_id' => Product::all()->random()->id,
                               'status_id' => Leadstatus::all()->random()->id,
                               'communication_id' => Communication::all()->random()->id,
                               'rating' => $faker->randomElement(['active', 'acquired', 'failed', 'blacklist']),
                               'viewed' => $faker->randomElement(['1', '0']),
                               'description' => $faker->realText(),
                               'mob_phone' => $faker->phoneNumber,
                               'organization' => $faker->company,
                               'homepage' => $faker->url,
                               'amount' => $faker->randomNumber,
                               'last_followed_date' => $faker->dateTime(),
                               'target_date' => $faker->dateTime(),
                               'dob' => $faker->dateTimeBetween('1970-01-01', '2002-12-31')->format('Y-m-d'),
                               'address_line_1' => $faker->streetAddress,
                               'position' => $faker->jobTitle,
                               'created_at' 		=> $faker->dateTimeBetween('2019-01-01', '2019-10-31'),
                              // 'department' => $faker->department,
                              // 'sector' => $faker->region,
                               'city' => $faker->city,
                               'last_followed_by' => User::all()->random()->id,
                               'user_id' => User::all()->random()->id,

                           ]);
        }
    }
}
