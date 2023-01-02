<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssemblyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assembly', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('org_id');
			$table->integer('user_id');
			$table->integer('product');
			$table->enum('status', array('parked','completed'));
			$table->string('total_amount');
			$table->string('comments')->nullable();
			$table->integer('source')->nullable();
			$table->integer('destination')->nullable();
			$table->string('assembled_quantity')->nullable();
			$table->string('can_assemble_qty')->nullable();
			$table->string('can_assemble_qty_all_levels')->nullable();
			$table->string('total_cost')->nullable();
			$table->string('assemble_by')->nullable();
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
		Schema::drop('assembly');
	}

}
