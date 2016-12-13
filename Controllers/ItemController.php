<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Input; //For file input
use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$blogs = DB::table('admin_blogs')
			->join('users', 'admin_blogs.user_id', '=', 'users.id')
			->select('admin_blogs.*', 'admin_blogs.id as blogId', 'users.id', 'users.name', 'users.imagePath as gravatar')
			->orderby('admin_blogs.created_at', 'DSC')
			->get();

		return view('adminBlog', ['adminBlogs' => $blogs]);
	}


    public function devices()
    {
        $title = "Devices: thin clients, PCs, kiosks, etc.";
        $itemType = 'device';
        $items = DB::table('items')
            ->join('users', 'items.user_id', '=', 'users.id')
            ->select('items.*', 'items.id as itemId', 'users.id as userId', 'users.name as author', 'users.imagePath as gravatar')
            ->where('items.type', '=', 'device')
            ->where('items.approved', '=', 1)
            ->orderby('items.created_at', 'DSC')
            ->get();

        return view('items', ['items' => $items, 'itemType' => $itemType, 'title' => $title]);
    }

	public function phones()
	{
		$title = "Phones: Cisco, Avaya, Magix, Nortel";
		$itemType = 'phone';

		$items = DB::table('items')
			->join('users', 'items.user_id', '=', 'users.id')
			->select('items.*', 'items.id as itemId', 'users.id as userId', 'users.name as author', 'users.imagePath as gravatar')
			->where('items.type', '=', 'phone')
			->where('items.approved', '=', 1)
			->orderby('items.created_at', 'DSC')
			->get();

		return view('items', ['items' => $items, 'itemType' => $itemType, 'title' => $title]);
	}

	public function switches()
	{
		$title = "Switches";
		$itemType = 'switch';
		$items = DB::table('items')
			->join('users', 'items.user_id', '=', 'users.id')
			->select('items.*', 'items.id as itemId', 'users.id as userId', 'users.name as author', 'users.imagePath as gravatar')
			->where('items.type', '=', 'switch')
			->where('items.approved', '=', 1)
			->orderby('items.created_at', 'DSC')
			->get();

		return view('items', ['items' => $items, 'itemType' => $itemType, 'title' => $title]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
    public function create($type)
    {
        //
        $action = 'create';
        //$type = $type;
        $title = '';
        $description = '';
        $imagePath = '';
        $documentPath = '';

        return view('createUpdateItemForm', [
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'action' => $action,
            'imagePath' => $imagePath,
            'documentPath' => $documentPath,
        ]);
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
    public function store(Request $request)
    {
        $type = $request->type;

        $title = $request->title;
        $description = $request->description;
        $role = $request->role;
        $approved = '0';

        if ($role == 'admin') ($approved = '1');

        $action = 'create';

        $this->validate($request, [
            'type' => 'required',
            'title' => 'required|max:255',
            'description' => 'required',
            'imagePath' => 'max:50000000|mimes:jpg,jpeg,png', //mimes don't work, need to change php.ini file
            'documentPath' => 'mimes:doc,docx,txt',
            'user_id' => 'required',

        ]);

        //Check if image path correct type(.jpg, .gif, etc), else return to create view.
        $newName = '';
        $newDocumentName = '';

        if (Input::file('imagePath')) {
            $file = Input::file('imagePath'); // need use input;
            $file_name = $file->getClientOriginalName();
            $file_ex = $file->getClientOriginalExtension();
            if (!in_array($file_ex, array('jpg', 'gif', 'png')))
                return view('createUpdateItemForm', [
                    'type' => $type,
                    'title' => $title,
                    'description' => $description,
                    'action' => $action,
                    'imagePath' => '',
                    'documentPath' => '',
                ])->withErrors('Invalid image extension we just allow JPG, GIF, PNG');
            //->withErrors('Invalid image extension we just allow JPG, GIF, PNG')
            //Append time to image name..thus no duplicate error..his means hours min and seconds
            $getTime = date("his");
            $newName = $getTime . $file_name;
            $file->move(base_path() . '/public/images/', $newName);
        };

        if (Input::file('documentPath')) {
            $file = Input::file('documentPath'); // need use input;
            $file_name = $file->getClientOriginalName();
            $file_ex = $file->getClientOriginalExtension();
            if (!in_array($file_ex, array('doc', 'docx', 'txt', 'pdf')))
                return view('createUpdateItemForm', [
                    'type' => $type,
                    'title' => $title,
                    'description' => $description,
                    'action' => $action,
                    'imagePath' => '',
                    'documentPath' => '',
                ])->withErrors('Invalid document extension we just allow JPG, GIF, PNG');
            //->withErrors('Invalid image extension we just allow JPG, GIF, PNG')
            //Append time to image name..thus no duplicate error..his means hours min and seconds
            $getTime = date("his");
            $newDocumentName = $getTime . $file_name;

            $file->move(base_path() . '/public/documents/', $newDocumentName);
        };

        $item = Item::create([
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'imagePath' => $newName,
            'documentPath' => $newDocumentName,
            'approved' => $approved,

        ]);
        if ($type == 'switch')
            return redirect('/' . $type . 'es');
        else
            return redirect('/' . $type . 's');
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return Response
	 */
    public function edit($id)
    {
        $item = item::find($id);

        $action = 'update';
        $type = $item->type;
        $title = $item->title;
        $description = $item->description;
        $imagePath = $item->imagePath;
        $documentPath = $item->documentPath;

        return view('createUpdateItemForm', [
            'action' => $action,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'imagePath' => $imagePath,
            'documentPath' => $documentPath,
            'itemId' => $id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $type = $request->type;
        $title = $request->title;
        $description = $request->description;
        $id = $request->itemId;
        $role = $request->role;
        $action = 'update';

        $approved = '0';
        if ($role == 'admin') ($approved = '1');

        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'imagePath' => 'max:50000000', //mimes don't work, need to change php.ini file
            //'documentPath' => 'max:1000|mimes:doc,docx',
            'user_id' => 'required',
            'role' => 'required'
        ]);

        //Check if image path correct type(.jpg, .gif, etc), else return to create view.
        $item = Item::find($id);
        $newName = $item->imagePath;
        $documentNewName = $item->documentPath;


        if (Input::file('imagePath')) {
            $file = Input::file('imagePath'); // need use input;
            $file_name = $file->getClientOriginalName();
            $file_ex = $file->getClientOriginalExtension();
            if (!in_array($file_ex, array('jpg', 'gif', 'png')))
                return view('createUpdateItemForm', [
                    'itemId' => $id,
                    'type' => $type,
                    'title' => $title,
                    'description' => $description,
                    'action' => $action,
                    'imagePath' => '',
                    'documentPath' => '',
                ])->withErrors('Invalid image extension we just allow JPG, GIF, PNG');
            //->withErrors('Invalid image extension we just allow JPG, GIF, PNG')
            //Append time to image name..thus no duplicate error..his means hours min and seconds
            $getTime = date("his");
            $newName = $getTime . $file_name;
            $file->move(base_path() . '/public/images/', $newName);
        };

        if (Input::file('documentPath')) {
            $file = Input::file('documentPath'); // need use input;
            $file_name = $file->getClientOriginalName();
            $file_ex = $file->getClientOriginalExtension();
            if (!in_array($file_ex, array('doc', 'docx', 'txt', 'pdf')))
                return view('createUpdateItemForm', [
                    'itemId' => $id,
                    'type' => $type,
                    'title' => $title,
                    'description' => $description,
                    'action' => $action,
                    'imagePath' => '',
                    'documentPath' => '',
                ])->withErrors('Invalid document extension we just allow JPG, GIF, PNG');
            //->withErrors('Invalid image extension we just allow JPG, GIF, PNG')
            //Append time to image name..thus no duplicate error..his means hours min and seconds
            $getTime = date("his");
            $newDocumentName = $getTime . $file_name;

            $file->move(base_path() . '/public/documents/', $newDocumentName);
        };

        //Everything is validated, get Blog and update it
        $item = Item::find($id);
        $item->type = $request->type;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->user_id = $request->user_id;
        $item->imagePath = $newName;
        $item->documentPath = $documentNewName;
        $item->approved = $approved;
        $item->save();
        return redirect('/' . $type . 's');
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $item = Item::find($id);
        $type = $item->type;
        Item::destroy($id);
        return redirect('/' . $type . 's');
    }

}