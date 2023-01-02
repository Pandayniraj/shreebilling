<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDartaChalaniFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('darta_chalani_files', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->enum('type', array('darta','chalani'));
			$table->integer('parent_id');
			$table->string('attachment', 300);
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
		Schema::drop('darta_chalani_files');
	}

}
