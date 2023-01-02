<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblLeaveCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_leave_category', function(Blueprint $table)
		{
			$table->integer('leave_category_id', true);
			$table->string('leave_category', 100);
			$table->integer('leave_quota');
			$table->string('leave_code')->nullable();
			$table->string('leave_type')->nullable();
			$table->string('lapse_type')->nullable();
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
		Schema::drop('tbl_leave_category');
	}

}
