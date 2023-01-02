<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('documents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('folder_id');
			$table->integer('contact_id');
			$table->string('file');
			$table->enum('doc_type', array('docs','note','noteDoc'));
			$table->enum('doc_cats', array('sales','marketing','development','customer','operation'));
			$table->string('doc_name');
			$table->text('doc_desc');
			$table->boolean('show_in_portal')->default(0);
			$table->boolean('enabled')->default(0);
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
		Schema::drop('documents');
	}

}
