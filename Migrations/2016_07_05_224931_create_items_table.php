<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('items', function(Blueprint $table)
        {
            $table->increments('id');
            $table->enum('type', ['device', 'phone','ap','switch','news']);
            $table->string('title',40);  //max length 40
            $table->text('description');
            $table->string('imagePath',255)->nullable();
            $table->string('documentPath',255)->nullable();
            $table->boolean('approved')->default(false);
            $table->integer('user_id');
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
		Schema::drop('items');
	}

}
