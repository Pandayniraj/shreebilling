<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasePart2Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_part2', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('case_id');
			$table->timestamp('visit_date_time')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('service_engineer');
			$table->string('call_status');
			$table->string('peding_reasons');
			$table->string('remarks');
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
		Schema::drop('case_part2');
	}

}
