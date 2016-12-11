<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Comment extends Model {

    protected $fillable = ['item_id', 'body', 'type', 'user_id'];
	//
    /**
     * Get Count of comments for an Item
     */
    public static function get_count($id, $type)
    {
        //return $this->belongsTo(User::class);
        return DB::table('comments')
            ->where('item_id','=', $id)
            ->where('type','=',$type)
            ->count('user_id');
    }

}
