<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
//use App\Item;
use Illuminate\Http\Request;

class SearchController extends Controller {

    public function search(Request $request)
    {
        $items = array();
        $code_blogs = array();

        If ($request->search == ''){
            $html = 'Please enter a valid search.';
            return view('search', ['items' => $items, 'html' => $html]);
        } else {
            $items = DB::table('items')
                ->join('users', 'items.user_id', '=', 'users.id')
                ->select('items.*','items.id as itemId','users.id as userId', 'users.name as author', 'users.imagePath as gravatar')
                ->where('items.approved', '=', 1)
                ->where('items.title', 'like', '%' . $request->search . '%')
                ->orwhere('items.description', 'like', '%' . $request->search . '%')
                ->orderby('items.created_at', 'DSC')
                ->get();
            $code_blogs = DB::table('code_blogs')
                ->select('code_blogs.*')
                ->where('code_blogs.title', 'like', '%' . $request->search . '%')
                ->orwhere('code_blogs.description', 'like', '%' . $request->search . '%')
                ->orwhere('code_blogs.description2', 'like', '%' . $request->search . '%')
                ->orwhere('code_blogs.code', 'like', '%' . $request->search . '%')
                ->orwhere('code_blogs.code2', 'like', '%' . $request->search . '%')
                ->orderby('code_blogs.created_at', 'DSC')
                ->get();
        }

        if (count($items) == 0 && count($code_blogs) == 0){
            $html = 'Nothing found!';
        } else {
            $html = 'Results Found';
            //Search string "Hello World!", find the value "world", replace with "Peter".
            //str_replace("world","Peter","Hello world!");
            foreach ($items as $item){
                //highlight search words found
                $item->title = str_replace($request->search,"<mark>" . $request->search . '</mark>',$item->title);
                //convert title to a link
                //$item->title = "<a href="devices#device3"
                $item->title = '<a href="' . $item->type . 's#' . $item->type . $item->id . '">' . $item->title . '</a>';
                $item->description = str_replace($request->search,"<mark>" . $request->search . '</mark>',$item->description);
            }
            foreach ($code_blogs as $code_blog){
                $code_blog->title = str_replace($request->search,"<mark>" . $request->search . '</mark>',$code_blog->title);
                //$pageName . $code_blog->id
                $code_blog->title = '<a href="Tutorial/' . $code_blog->pageName . '#' . $code_blog->pageName . $code_blog->id . '">' . $code_blog->title . '</a>';
                $code_blog->description = str_replace($request->search,"<mark>" . $request->search . '</mark>',$code_blog->description);
                $code_blog->description2 = str_replace($request->search,"<mark>" . $request->search . '</mark>',$code_blog->description2);
            }
        }

        return view('search', ['items' => $items, 'code_blogs'=>$code_blogs, 'html' => $html, 'search' => $request->search]);
    }


/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
	}

}
