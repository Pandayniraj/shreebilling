<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('ProductionSeeder');

        // Example of how to call a seeder script for a given environment.
//        if( App::environment() === 'development' )
//        {
//            $this->call('DevelopmentSeeder');
//        }

        Model::reguard();
    }
}
