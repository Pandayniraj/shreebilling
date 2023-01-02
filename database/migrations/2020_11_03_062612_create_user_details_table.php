<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->string('contract_start_date')->nullable();
			$table->string('contract_end_date')->nullable();
			$table->string('father_name')->nullable();
			$table->string('present_address')->nullable();
			$table->string('gender')->nullable();
			$table->string('marital_status')->nullable();
			$table->string('mother_name')->nullable();
			$table->string('bank_name')->nullable();
			$table->string('bank_account_name')->nullable();
			$table->string('bank_account_no')->nullable();
			$table->string('bank_account_branch')->nullable();
			$table->string('education', 355)->nullable();
			$table->string('skills', 455)->nullable();
			$table->string('id_proof')->nullable();
			$table->string('resume')->nullable();
			$table->string('date_of_birth')->nullable();
			$table->string('nationality')->nullable();
			$table->string('license_number')->nullable();
			$table->string('food')->nullable();
			$table->string('blood_group')->nullable();
			$table->string('emergency_contact_name')->nullable();
			$table->string('relationship')->nullable();
			$table->string('mobile')->nullable();
			$table->string('work_phone')->nullable();
			$table->string('amount')->nullable();
			$table->string('routing_num')->nullable();
			$table->string('join_date')->nullable();
			$table->string('date_of_probation')->nullable();
			$table->string('date_of_permanent')->nullable();
			$table->string('last_promotion_date')->nullable();
			$table->string('last_transfer_date')->nullable();
			$table->string('date_of_retirement')->nullable();
			$table->string('working_status')->nullable();
			$table->string('job_title')->nullable();
			$table->string('employemnt_type')->nullable();
			$table->string('document_type')->nullable();
			$table->string('document_number')->nullable();
			$table->string('document_issue')->nullable();
			$table->string('street')->nullable();
			$table->string('ward_no')->nullable();
			$table->string('municipality')->nullable();
			$table->string('district_id')->nullable();
			$table->string('province_id')->nullable();
			$table->string('country_id')->nullable();
			$table->boolean('viber')->nullable();
			$table->boolean('whatsapp')->nullable();
			$table->string('document_image')->nullable();
			$table->string('citizenship_image_back')->nullable();
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
		Schema::drop('user_details');
	}

}
