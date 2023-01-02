<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayFrequencyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pay_frequency', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name');
			$table->string('frequency');
			$table->integer('fiscal_year_id');
			$table->date('check_date');
			$table->date('period_start_date');
			$table->date('period_end_date');
			$table->integer('user_id');
			$table->boolean('is_issued')->default(0);
			$table->string('time_entry_method', 100);
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
		Schema::drop('pay_frequency');
	}

}
