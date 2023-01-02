<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('knowledge', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('author_id');
			$table->integer('cat_id');
			$table->string('title');
			$table->string('description');
			$table->text('body');
			$table->integer('related_case');
			$table->integer('view_count');
			$table->boolean('enabled')->default(0);
			$table->timestamp('expire_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamps(10);
			$table->integer('org_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('knowledge');
	}

}
