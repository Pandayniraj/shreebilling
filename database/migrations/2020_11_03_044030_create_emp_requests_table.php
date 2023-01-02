<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('emp_requests', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('title');
			$table->integer('emp_id');
			$table->string('request_type');
			$table->string('benefit_type');
			$table->string('pay_type');
			$table->float('cost', 10, 0);
			$table->text('description');
			$table->string('status');
			$table->text('comment')->nullable();
			$table->string('attachment', 300);
			$table->integer('request_team');
			$table->integer('approved_by')->nullable();
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
		Schema::drop('emp_requests');
	}

}
