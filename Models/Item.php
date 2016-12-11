<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Item extends Model {

	//
    protected $fillable = ['type','title', 'description', 'imagePath', 'documentPath','user_id','approved'];

    public static function get_pending_count()
    {
        return DB::table('items')
            ->where('approved','=', 0)
            ->count('id');
    }


}
