<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {

	//
    protected $fillable = ['customerID', 'siteTypeID','siteNumber', 'countryID',
        'stateID', 'cityID', 'address', 'zip','notes','concurrentUserID' ];
}
