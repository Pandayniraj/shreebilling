<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_groups', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name');
			$table->integer('org_id');
			$table->integer('user_id');
			$table->text('description');
			$table->integer('enabled')->default(1);
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
		Schema::drop('customer_groups');
	}

}
