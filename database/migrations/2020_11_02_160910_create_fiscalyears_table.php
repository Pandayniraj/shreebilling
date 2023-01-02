<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiscalyearsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fiscalyears', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('org_id')->nullable();
			$table->string('fiscal_year');
			$table->string('numeric_fiscal_year');
			$table->date('start_date');
			$table->date('end_date');
			$table->boolean('closed')->default(0);
			$table->boolean('current_year');
			$table->date('updated_at');
			$table->date('created_at');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fiscalyears');
	}

}
