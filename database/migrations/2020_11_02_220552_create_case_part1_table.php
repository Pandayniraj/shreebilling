<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasePart1Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_part1', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('case_id');
			$table->string('part_name');
			$table->string('part_code');
			$table->text('description');
			$table->integer('quantity');
			$table->float('rate', 10, 0);
			$table->float('amount', 10, 0);
			$table->string('remark');
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
		Schema::drop('case_part1');
	}

}
