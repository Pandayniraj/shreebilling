<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entries', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('org_id')->nullable();
			$table->bigInteger('tag_id')->nullable()->index('tag_id');
			$table->integer('project_id')->nullable();
			$table->bigInteger('entrytype_id')->index('entrytype_id');
			$table->string('number', 30)->nullable();
			$table->date('date');
			$table->string('currency', 20)->nullable()->default('NPR');
			$table->decimal('dr_total', 25)->default(0.00);
			$table->decimal('cr_total', 25)->default(0.00);
			$table->string('notes', 500);
			$table->string('source', 50)->nullable();
			$table->integer('fiscal_year_id');
			$table->timestamps(10);
			$table->integer('user_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('entries');
	}

}
