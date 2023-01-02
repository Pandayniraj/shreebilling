<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblOvertimeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_overtime', function(Blueprint $table)
		{
			$table->integer('overtime_id', true);
			$table->integer('user_id');
			$table->date('overtime_date');
			$table->string('overtime_hours', 20);
			$table->text('notes')->nullable();
			$table->enum('status', array('pending','approved','rejected'))->default('pending');
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
		Schema::drop('tbl_overtime');
	}

}
