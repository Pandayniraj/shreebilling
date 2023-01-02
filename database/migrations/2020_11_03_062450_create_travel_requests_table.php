<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('travel_requests', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('place_of_visit');
			$table->date('depart_date');
			$table->date('arrival_date');
			$table->string('num_days');
			$table->float('travel_cost', 10, 0);
			$table->boolean('is_billable_to_customer');
			$table->string('customer_name');
			$table->integer('business_account');
			$table->string('phone_num');
			$table->integer('user_id');
			$table->integer('staff_id');
			$table->string('status');
			$table->text('visit_purpose');
			$table->text('remarks');
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
		Schema::drop('travel_requests');
	}

}
