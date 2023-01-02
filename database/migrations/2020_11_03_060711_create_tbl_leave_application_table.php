<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblLeaveApplicationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_leave_application', function(Blueprint $table)
		{
			$table->integer('leave_application_id', true);
			$table->integer('user_id');
			$table->integer('leave_category_id');
			$table->text('reason');
			$table->integer('leave_days');
			$table->date('leave_start_date');
			$table->date('leave_end_date');
			$table->integer('application_status')->default(1)->comment('1=pending,2=accepted 3=rejected');
			$table->boolean('view_status');
			$table->timestamp('application_date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->text('attachment')->nullable();
			$table->text('comments')->nullable();
			$table->integer('approve_by')->nullable();
			$table->integer('request_to')->nullable()->comment('Teams To send Leave request');
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
		Schema::drop('tbl_leave_application');
	}

}
