<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadMobilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lead_mobiles', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('lead_id');
			$table->string('name')->nullable();
			$table->string('email')->nullable();
			$table->string('mobile')->nullable();
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
		Schema::drop('lead_mobiles');
	}

}
