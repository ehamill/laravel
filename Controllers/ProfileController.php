<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Hash;
use Input;
use DB;
use App\Item;
use Illuminate\Http\Request;

class ProfileController extends Controller {

	/*
	 * User must be authenticated, else page will only display login
	 */
	public function __construct()
	{
		$this->middleware('auth');

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
	public function show()
	{
		if(\Auth::check() ){
			return view('account/profile', ['user' => User::findOrFail(\Auth::user()->id)]);
		} else {
			return redirect('/');
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit()
	{
		if(\Auth::check() ){
			return view('account/profile', ['user' => User::findOrFail(\Auth::user()->id)]);
		} else {
			return redirect('/');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
    public function update(Request $request)
    {
        // Get user
        $user = User::findOrFail(\Auth::user()->id);

        $this->validate($request, [
            //Ignore a given keyId...unique:<table_name>,<field>,' . <keyid>,
            'name' => 'required| unique:users,name,'. $user->id,
            'email' => 'required|max:255|unique:users,email,' . $user->id,
            'imagePath' => 'max:50000000|mimes:jpg,jpeg,png,gif,bmp',
            'password'=> 'confirmed|min:6'
            ]);

        //save user's gravatar, personal image
        $newFileName = $user->imagePath;
        if (Input::file('imagePath')) {
            $file = Input::file('imagePath'); // need use input;
            $file_name = $file->getClientOriginalName();
            //Append time to image name..thus no duplicate error..his means hours min and seconds
            $getTime = date("his");
            $newFileName = $getTime . $file_name;
            $file->move(base_path().'/public/images/gravatars', $newFileName);
        };

        //SAVE FIELDS
        $user->name = $request->name;
        $user->email = $request->email;
        $user->jobTitle = $request->jobTitle;
        //if password field empty, do nothing, else save password
        if($request->password != ''){
            $user->password = Hash::make($request->password);
        }
        $user->imagePath = $newFileName;
        //$user->documentPath = $documentNewName;
        //$user->approved = $approved;
        $user->save();
        return redirect('/');


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

	public function admin()
	{
		$users = User::all();

		if(\Auth::check() && (\Auth::user()->role == 'admin')){
			return view('account/admin', ['users' => $users]);
		} else {
			return redirect('/');
		}
	}

	public function pending()
	{
		$items = DB::table('items')
			->join('users', 'items.user_id', '=', 'users.id')
			->select('items.*','items.id as itemId','users.id as userId', 'users.name as author', 'users.imagePath as gravatar')
			->where('items.approved', '=', 0)
			->orderby('items.created_at', 'DSC')
			->get();

		//double check to make sure user logged in and admin
		if(\Auth::check() && (\Auth::user()->role == 'admin')){
			return view('account/pendingList', ['items' => $items]);
		} else {
			return redirect('/');
		}
	}

	public function approve_post(Request $request)
	{
		$id = $request->itemId;
		$item = Item::find($id);
		$item->approved = 1;
		$item->save();
	}

	public function delete_post(Request $request)
	{
		$id = $request->itemId;
		Item::destroy($id);
	}

	public function change_role(Request $request)
	{
		$user = User::find($request->userId);
		$user->role = $request->role;
		$user->save();
	}

	public function change_password(Request $request)
	{
		$user = User::find($request->userId);
		if($request->password != ''){
			$user->password = Hash::make($request->password);
		}
		$user->save();
	}
}
