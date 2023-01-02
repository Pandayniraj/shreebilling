<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillofmaterialsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('billofmaterials', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('org_id');
			$table->integer('user_id');
			$table->string('bill_date')->nullable();
			$table->integer('product');
			$table->enum('status', array('parked','completed'))->default('parked');
			$table->integer('auto_assemble')->nullable()->default(0);
			$table->integer('auto_disassemble')->nullable()->default(0);
			$table->integer('obsolete')->nullable()->default(0);
			$table->string('total_amount');
			$table->string('comments')->nullable();
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
		Schema::drop('billofmaterials');
	}

}
