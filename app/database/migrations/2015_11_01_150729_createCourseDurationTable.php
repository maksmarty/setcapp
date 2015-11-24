<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseDurationTable extends Migration {


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
			$table->string("level");
			$table->string("date");
			$table->string("time");
			$table->string("location");
			$table->tinyInteger('status')->unsigned()->default('1')->comment('1=Active,0=Inactive');
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
