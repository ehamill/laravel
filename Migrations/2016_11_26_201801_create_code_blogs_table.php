<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeBlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('code_blogs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('priority');
			$table->string('pageName');
			$table->string('title')->nullable();
			$table->text('description')->nullable();
			$table->text('code')->nullable();
			$table->text('description2')->nullable();
			$table->text('code2')->nullable();
			$table->string('imagePath',255)->nullable();
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
		Schema::drop('code_blogs');
	}

}
