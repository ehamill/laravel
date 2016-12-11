<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CodeComment extends Model {

    protected $fillable = ['pageName', 'comment', 'code', 'user_id'];
    //
    /**
     * Get Count of comments for an Item
     */
    public static function get_count_code_blog_comments($pageName)
    {
        //return $this->belongsTo(User::class);
        return DB::table('code_comments')
            ->where('pageName','=', $pageName)
            ->count('id');
    }


}
