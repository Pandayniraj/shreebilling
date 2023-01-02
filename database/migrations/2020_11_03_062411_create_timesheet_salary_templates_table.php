<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetSalaryTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timesheet_salary_templates', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('org_id');
			$table->string('salary_grade');
			$table->float('salary_per_hour', 10, 0);
			$table->float('overtime_salary_per_hour', 10, 0);
			$table->float('other_salary_per_hour', 10, 0);
			$table->text('remarks');
			$table->boolean('enabled');
			$table->integer('user_id');
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
		Schema::drop('timesheet_salary_templates');
	}

}
