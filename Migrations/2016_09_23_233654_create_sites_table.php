<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('sites', function(Blueprint $table)
        {
            ////Site Table = id, customerID, siteNumber, siteType,countryID,stateID, cityID,address, zip
            $table->increments('id');
            $table->integer('customerID');
            $table->string('siteNumber',40);
            $table->integer('siteTypeID');
            $table->integer('countryID');
            $table->integer('stateID');
            $table->integer('cityID');
            $table->string('address');
            $table->integer('zip');
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
		Schema::drop('sites');
	}

}
