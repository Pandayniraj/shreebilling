<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAwardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_awards', function(Blueprint $table)
		{
			$table->integer('award_id', true);
			$table->integer('user_id');
			$table->string('award_name')->nullable();
			$table->string('gift_item')->nullable();
			$table->string('cash_price')->nullable();
			$table->string('month')->nullable();
			$table->string('award_date')->nullable();
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
		Schema::drop('employee_awards');
	}

}
