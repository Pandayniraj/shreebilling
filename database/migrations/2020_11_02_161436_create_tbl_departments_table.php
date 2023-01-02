<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDepartmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_departments', function(Blueprint $table)
		{
			$table->integer('departments_id', true);
			$table->integer('org_id');
			$table->string('deptname', 200)->nullable();
			$table->integer('department_head_id')->nullable()->comment('department_head_id == user_id');
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
		Schema::drop('tbl_departments');
	}

}
