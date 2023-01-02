<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('projects_cat')->nullable();
			$table->string('name');
			$table->string('staffs');
			$table->integer('org_id')->nullable();
			$table->string('class')->nullable();
			$table->integer('assign_to');
			$table->text('description');
			$table->timestamp('start_date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('end_date');
			$table->string('status', 50);
			$table->boolean('enabled');
			$table->timestamps(10);
			$table->string('tagline')->nullable();
			$table->string('youtube')->nullable();
			$table->string('website1')->nullable();
			$table->string('website2')->nullable();
			$table->string('website3')->nullable();
			$table->string('facebook1')->nullable();
			$table->string('facebook2')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects');
	}

}
