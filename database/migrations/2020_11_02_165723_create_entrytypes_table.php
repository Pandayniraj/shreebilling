<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntrytypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entrytypes', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('org_id');
			$table->string('label');
			$table->string('name');
			$table->string('description');
			$table->integer('base_type')->default(0);
			$table->integer('numbering')->default(1);
			$table->string('prefix');
			$table->string('suffix');
			$table->integer('zero_padding')->default(0);
			$table->integer('restriction_bankcash')->default(1);
			$table->timestamps(10);
			$table->unique(['org_id','label'], 'entrytypes_org_based');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('entrytypes');
	}

}
