<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('job_applications', function(Blueprint $table)
		{
			$table->integer('job_appliactions_id', true);
			$table->integer('job_circular_id');
			$table->string('name', 200);
			$table->string('email', 100);
			$table->string('mobile', 15);
			$table->text('cover_letter');
			$table->text('resume');
			$table->boolean('application_status')->default(0)->comment('0=pending 1=accept 2 = reject  3 = call for interview 4 = primary selection');
			$table->timestamps(10);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('job_applications');
	}

}
