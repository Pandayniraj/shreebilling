<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceApprisalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('performance_apprisal', function(Blueprint $table)
		{
			$table->integer('performance_appraisal_id', true);
			$table->integer('user_id');
			$table->text('general_remarks');
			$table->string('appraisal_month', 100);
			$table->boolean('customer_experiece_management')->nullable()->comment('Technical - 1 = Beginner, 2 = Intermediate, 3 = Advanced, 4 = Expert / Leader');
			$table->boolean('marketing')->nullable()->comment('Technical - 1 = Beginner, 2 = Intermediate, 3 = Advanced, 4 = Expert / Leader');
			$table->boolean('management')->nullable()->comment('Technical - 1 = Beginner, 2 = Intermediate, 3 = Advanced, 4 = Expert / Leader');
			$table->boolean('administration')->nullable()->comment('Technical - 1 = Beginner, 2 = Intermediate, 3 = Advanced, 4 = Expert / Leader');
			$table->boolean('presentation_skill')->nullable()->comment('Technical - 1 = Beginner, 2 = Intermediate, 3 = Advanced, 4 = Expert / Leader');
			$table->boolean('quality_of_work')->nullable()->comment('Technical - 1 = Beginner, 2 = Intermediate, 3 = Advanced, 4 = Expert / Leader');
			$table->boolean('efficiency')->nullable()->comment('Technical - 1 = Beginner, 2 = Intermediate, 3 = Advanced, 4 = Expert / Leader');
			$table->boolean('integrity')->nullable()->comment('Behavioural - 1 (S) = Satisfactory, 2 (U) = Unsatisfactory, 3 (N) = Needs Improvement');
			$table->boolean('professionalism')->nullable()->comment('Behavioural - 1 (S) = Satisfactory, 2 (U) = Unsatisfactory, 3 (N) = Needs Improvement');
			$table->boolean('team_work')->nullable()->comment('Behavioural - 1 (S) = Satisfactory, 2 (U) = Unsatisfactory, 3 (N) = Needs Improvement');
			$table->boolean('critical_thinking')->nullable()->comment('Behavioural - 1 (S) = Satisfactory, 2 (U) = Unsatisfactory, 3 (N) = Needs Improvement');
			$table->boolean('conflict_management')->nullable()->comment('Behavioural - 1 (S) = Satisfactory, 2 (U) = Unsatisfactory, 3 (N) = Needs Improvement');
			$table->boolean('attendance')->nullable()->comment('Behavioural - 1 (S) = Satisfactory, 2 (U) = Unsatisfactory, 3 (N) = Needs Improvement');
			$table->boolean('ability_to_meed_deadline')->nullable()->comment('Behavioural - 1 (S) = Satisfactory, 2 (U) = Unsatisfactory, 3 (N) = Needs Improvement');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('performance_apprisal');
	}

}
