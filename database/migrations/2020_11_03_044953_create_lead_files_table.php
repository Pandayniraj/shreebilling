<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lead_files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('file');
			$table->integer('lead_id')->unsigned()->default(0)->index('lead_files_lead_id_foreign');
			$table->integer('user_id')->unsigned()->default(1)->index('lead_files_user_id_foreign');
			$table->boolean('enabled')->default(1);
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
		Schema::drop('lead_files');
	}

}
