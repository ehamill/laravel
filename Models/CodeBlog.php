<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CodeBlog extends Model {

	//
    protected $fillable = ['pageName', 'title','description', 'code','description2', 'code2', 'imagePath','priority'];

}
