<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTrainingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_training', function(Blueprint $table)
		{
			$table->integer('training_id', true);
			$table->integer('user_id');
			$table->integer('assigned_by');
			$table->integer('org_id')->nullable();
			$table->string('training_name', 100);
			$table->string('vendor_name', 100);
			$table->date('start_date');
			$table->date('finish_date');
			$table->string('training_cost', 300)->nullable();
			$table->boolean('status')->default(0)->comment('0 = pending, 1 = started, 2 = completed, 3 = terminated');
			$table->boolean('performance')->nullable()->default(0)->comment('0 = not concluded, 1 = satisfactory, 2 = average, 3 = poor, 4 = excellent');
			$table->text('remarks');
			$table->text('upload_file');
			$table->text('permission')->nullable();
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
		Schema::drop('tbl_training');
	}

}
