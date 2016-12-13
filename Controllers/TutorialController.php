<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CodeBlog;
use App\CodeComment;
use DB;
use Input;

class TutorialController extends Controller {


        /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //
        if ($request->pageName == 'LaraIntro') {$title = 'Intoduction to Laravel';}
        if ($request->pageName == 'LaraUserAccounts') {$title = 'Modifying the User Profile';}
        if ($request->pageName == 'LaraBlog') {$title = 'Creating a Blog';}
        if ($request->pageName == 'LaraAjax') {$title = 'Using AJAX in Laravel';}
        if ($request->pageName == 'LaraDropDown') {$title = 'DropDown Hell';}
        if ($request->pageName == 'LaraErrors') {$title = 'Errors';}
        if ($request->pageName == 'LaraSearch') {$title = 'Creating a Search box';}

        $pageName = $request->pageName;
       // return view('userAccounts');
        $code_blogs = DB::table('code_blogs')
            ->where('pageName', '=',$pageName)
            ->orderby('code_blogs.priority', 'ASC')
            ->get();

        $comments = DB::table('code_comments')
            ->join('users', 'code_comments.user_id', '=', 'users.id')
            ->select('code_comments.*','code_comments.id as commentId','users.id as userId', 'users.name as author',
                'users.imagePath as gravatar')
            ->where('code_comments.pageName','=',$pageName)
            ->orderby('code_comments.created_at', 'DSC')
            ->get();

        return view('tutorial.code_blogs', ['code_blogs' => $code_blogs,'title' => $title, 'pageName' => $pageName,
            'comments' => $comments ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        //
        return view('Tutorial.addCodeBlog', [ 'pageName' => $request->pageName, ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $newName = '';

        if (Input::file('imagePath')) {
            $file = Input::file('imagePath'); // need use input;
            $file_name = $file->getClientOriginalName();
            $file_ex = $file->getClientOriginalExtension();
            //Append time to image name..thus no duplicate error..his means hours min and seconds
            $getTime = date("his");
            $newName = $getTime . $file_name;
            $file->move(base_path().'/public/images/', $newName);
        };

        //protected $fillable = ['pageName', 'title','description', 'code', 'imagePath','priority'];
        //Add to table
        CodeBlog::create([
            'pageName' => $request->pageName,
            'title' => $request->title,
            'description' => $request->description,
            'description2' => $request->description2,
            'code' => $request->code,
            'code2' => $request->code2,
            'imagePath' => $newName,
            'priority' => $request->priority,
        ]);

        return redirect('Tutorial/' . $request->pageName);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $code_blog = CodeBlog::Find($id);
        return view('tutorial.editCodeBlog', ['code_blog' => $code_blog, 'pageName' => $code_blog->pageName]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        //Deal with image upload
        $newName = '';

        if (Input::file('imagePath')) {
            $file = Input::file('imagePath'); // need use input;
            $file_name = $file->getClientOriginalName();
            $file_ex = $file->getClientOriginalExtension();
            //Append time to image name..thus no duplicate error..his means hours min and seconds
            $getTime = date("his");
            $newName = $getTime . $file_name;
            $file->move(base_path().'/public/images/', $newName);
        };

        //
        $code_blog = CodeBlog::Find($request->id);

        $code_blog->priority = $request->priority;
        $code_blog->title = $request->title;
        $code_blog->description = $request->description;
        $code_blog->code = $request->code;
        $code_blog->description2 = $request->description2;
        $code_blog->code2 = $request->code2;
        $code_blog->imagePath = $newName;
        $code_blog->save();

        return redirect('Tutorial/' . $request->pageName);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $code_blog = CodeBlog::find($id);
        CodeBlog::destroy($id);
        return redirect('Tutorial/' . $code_blog->pageName );
    }


    public function addComment(Request $request)
    {
        CodeComment::create([
            'pageName' => $request->pageName,
            'comment' => $request->comment,
            'code' => $request->code,
            'user_id' => $request->userId,
        ]);

        return redirect('Tutorial/' . $request->pageName );
    }

    public function deleteComment($id)
    {
        //
        $comment = CodeComment::find($id);
        $pageName = $comment->pageName;
        CodeComment::destroy($id);
        return redirect('Tutorial/' . $pageName );
    }

}
