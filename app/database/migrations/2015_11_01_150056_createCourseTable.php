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
		Schema::create("registration", function($table)
		{
			$table->engine = "InnoDB";

			$table->increments("registration_id");
			$table->mediumInteger('course_id')->unsigned();
			//$table->mediumInteger('course_duration_id')->unsigned();
			$table->string('name');
			//$table->string('last_name')->nullable();
			$table->string('email');//->unique()
			$table->bigInteger('phone')->unsigned()->nullable();
			$table->text('comments')->nullable();
			$table->tinyInteger('status')->unsigned()->default('0')->comment('0=Ready to send,1=sent to admin,2=sent to both');
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
		Schema::dropIfExists("registration");
	}

}
