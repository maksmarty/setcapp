<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseTable extends Migration {


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("course", function($table)
		{
			$table->engine = "InnoDB";

			$table->increments("course_id");
			$table->mediumInteger('course_level_id')->unsigned();
			$table->mediumInteger('course_duration_id')->unsigned();
			$table->string('first_name');
			$table->string('last_name')->nullable();
			$table->string('email')->unique();
			$table->bigInteger('phone')->unsigned()->nullable();
			$table->text('comments')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("course");
	}

}
