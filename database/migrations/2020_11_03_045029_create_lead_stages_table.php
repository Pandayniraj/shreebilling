<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadStagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lead_stages', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name');
			$table->integer('ordernum')->nullable();
			$table->string('color', 250)->nullable();
			$table->boolean('enabled');
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
		Schema::drop('lead_stages');
	}

}
