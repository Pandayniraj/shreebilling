<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('organizations', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('organization_name')->nullable();
			$table->string('vat_id')->nullable();
			$table->string('tpid', 250)->nullable();
			$table->string('address')->nullable();
			$table->string('phone')->nullable();
			$table->string('email')->nullable();
			$table->string('logo');
			$table->boolean('enabled')->nullable();
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
		Schema::drop('organizations');
	}

}
