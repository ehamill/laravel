<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Item;

class AdminBlog extends Model {

	//
    protected $fillable = ['title', 'description', 'imagePath', 'documentPath','user_id'];

    /**
     * Get Count of comments for a Blog
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
