<?php namespace

App\Http\Controllers;

use App\City;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SiteType;
use DB;
use App\Comment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller {

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
	 * Load Drop down list with SiteTypes
	 *
	 * @return Response
	 */
	public function load_site_types(Request $request)
	{
		$customerID = $request->customerID;

		$siteTypes = DB::table('site_types')
			->select('site_types.*')
			->where('site_types.customerID','=',$customerID)
			//->orderby('comments.created_at', 'DSC')
			->get();

	/*	<select name="siteTypeDD" id="siteTypeDD"  >
			<option value="0">-- Choose a Site Type --</option>
			@foreach($siteTypes as $siteType)
			<option value="{{$siteType->id}}">
			{{$siteType->name}}
			</option>
			@endforeach
		</select> */
		$html = '';
		if ($siteTypes == NULL){
			$html .= '' . '<select><option>-- No Sites Found --</option> </select>';
		} else {
			$html .= '' .  ' <select name="siteTypeID" id="siteTypeDD"  >
					<option value="">-- Choose a Site Type --</option> ';
			foreach ($siteTypes as $siteType) {
				$html .= '<option value="' . $siteType->id . '">' . $siteType->name . '</option>';
			}
			$html .= '' .  '</select>';
		}
		return $html;
	}

	/**
	 * Load Drop down list with States
	 *
	 * @return Response
	 */
	public function load_states(Request $request)
	{
		$countryID = $request->countryID;
		//$token = $request->_token;
		//$siteTypes = NULL;

		$states = DB::table('states')
			->select('states.*')
			->where('states.countryID','=',$countryID)
			->orderby('states.StateName', 'DSC')
			->get();

		/*	<select name="siteTypeDD" id="siteTypeDD"  >
                <option value="0">-- Choose a Site Type --</option>
                @foreach($siteTypes as $siteType)
                <option value="{{$siteType->id}}">
                {{$siteType->name}}
                </option>
                @endforeach
            </select> */
		$html = '';
		if ($states == NULL){
			$html .= '' . '<select><option>-- No States Found --</option> </select>';
		} else {
			$html .= '' .  ' <select name="stateID" id="stateDD" onchange="loadCities(this.value)"  >
					<option value="">-- Choose a State --</option> ';
			foreach ($states as $state) {
				$html .= '<option value="' . $state->id . '">' . $state->StateName .  '</option>';
			}
			$html .= '' .  '</select>';
		}
		return $html;
	}

    //load states for site page
    public function load_states2(Request $request)
    {
        $countryID = $request->countryID;
        //$token = $request->_token;
        //$siteTypes = NULL;

        $states = DB::table('states')
            ->select('states.*')
            ->where('states.countryID','=',$countryID)
            ->orderby('states.StateName', 'DSC')
            ->get();

        /*	<select name="siteTypeDD" id="siteTypeDD"  >
                <option value="0">-- Choose a Site Type --</option>
                @foreach($siteTypes as $siteType)
                <option value="{{$siteType->id}}">
                {{$siteType->name}}
                </option>
                @endforeach
            </select> */
        $html = '';
        if ($states == NULL){
            $html .= '' . '<select><option>-- No States Found --</option> </select>';
        } else {
            $html .= '' .  ' <select name="stateID" id="stateDD" onchange="filterSites()" >
                    <option value="">-- Choose a State --</option> ';
            foreach ($states as $state) {
                $html .= '<option value="' . $state->id . '">' . $state->StateName .  '</option>';
            }
            $html .= '' .  '</select>';
        }
        return $html;
    }

	//Load cities as a DATALIST
	public function load_cities(Request $request)
	{
		$countryID = $request->countryID;
		$stateID = $request->stateID;

		$cities = DB::table('cities')
			->select('cities.*')
			->where('cities.countryID','=',$countryID)
			->where('cities.stateID','=',$stateID)
			->orderby('cities.name', 'DSC')
			->get();

		$html = '' . '<input type="text" name="cityName" list="cities"><label for="cities"><datalist id="cities"><select>';
;
		if ($cities == NULL){
			$html .= '' . '<option value="-- No Cities Found --"/>';
		} else {
			foreach ($cities as $city) {
								$html .= '' .  '<option value="' . $city->name . '" />';
			}
		}
		$html .= '' . '</select></datalist></label>';
		return $html;
	}

	/**
	 * Load Drop down list with Cities
	 *
	 * @return Response
	 */
	public function load_cities2(Request $request)
	{
		$countryID = $request->countryID;
		$stateID = $request->stateID;
		//$token = $request->_token;
		//$siteTypes = NULL;

		$cities = DB::table('cities')
			->select('cities.*')
			->where('cities.countryID','=',$countryID)
			->where('cities.stateID','=',$stateID)
			->orderby('cities.name', 'DSC')
			->get();

		//$cities = City::all();
		/*	<select name="siteTypeDD" id="siteTypeDD"  >
                <option value="0">-- Choose a Site Type --</option>
                @foreach($siteTypes as $siteType)
                <option value="{{$siteType->id}}">
                {{$siteType->name}}
                </option>
                @endforeach
            </select> */
		$html = '';
		if ($cities == NULL){
			$html .= '' . '<select><option>-- No Cities Found --</option> </select>';
		} else {
			$html .= '' .  ' <select name="citiesDD" id="citiesDD" >
					<option value="">-- Choose a City --</option> ';
			foreach ($cities as $city) {
				$html .= '<option value="' . $city->id . '">' . $city->name . '</option>';
			}
			$html .= '' .  '</select>';
		}
		return $html;
	}

	/**
	 * Load Drop down list with States
	 *
	 * @return Response
	 */
	public function add_city(Request $request)
	{
		$countryID = $request->countryID;
		$stateID = $request->stateID;
		$name = $request->name;

		//Validate...i passed siteType instead of name..but it still works..probly not a good idea
		$this->validate($request, [
			'countryID' => 'required',
			'stateID' => 'required',
	//		'name' => 'required|unique:cities,name,NULL,NULL,stateID,' . $request->stateID . '|max:255',
		//	'name' => 'required|unique:cities,name,NULL,NULL,countryID,' . $request->countryID . ',stateID,' . $request->stateID . '|max:255',
		]);

		//Add to table
		City::create([
			'name' => $request->name,
			'countryID' => $request->countryID,
			'stateID' => $request->stateID,
		]);

		$cities = DB::table('cities')
			->select('cities.*')
			->where('cities.countryID','=',$countryID)
			->where('cities.stateID','=',$stateID)
			->orderby('cities.name', 'DSC')
			->get();

		/*	<select name="siteTypeDD" id="siteTypeDD"  >
                <option value="0">-- Choose a Site Type --</option>
                @foreach($siteTypes as $siteType)
                <option value="{{$siteType->id}}">
                {{$siteType->name}}
                </option>
                @endforeach
            </select> */
		$html = '';
		if ($cities == NULL){
			$html .= '' . '<select><option>-- No Cities Found --</option> </select>';
		} else {
			$html .= '' .  ' <select name="citiesDD" id="citiesDD" >
					<option value="">-- Choose a City --</option> ';
			foreach ($cities as $city) {
				$html .= '<option value="' . $city->id . '">' . $city->name . '</option>';
			}
			$html .= '' .  '</select>';
		}
		return $html;
	}


	/**
	 *  Add new siteType to database, and reload siteType drop down list
	 *
	 * @return Response
	 */
	public function add_site_type(Request $request)
	{
		//Validate...i passed siteType instead of name..but it still works..probly not a good idea

		$this->validate($request, [
			'siteType' => 'required|unique:site_types,name,NULL,NULL,customerID,' . $request->customerID . '|max:255',
			'customerID' => 'required',
		]);

		//Add to table
		SiteType::create([
			'name' => $request->siteType,
			'customerID' => $request->customerID,
		]);

		//Reload siteTYpe drop down box
		$customerID = $request->customerID;

		$siteTypes = DB::table('site_types')
			->select('site_types.*')
			->where('site_types.customerID','=',$customerID)
			//->orderby('comments.created_at', 'DSC')
			->get();

		/*	<select name="siteTypeDD" id="siteTypeDD"  >
                <option value="0">-- Choose a Site Type --</option>
                @foreach($siteTypes as $siteType)
                <option value="{{$siteType->id}}">
                {{$siteType->name}}
                </option>
                @endforeach
            </select>*/
		$html = '';
		if ($siteTypes == NULL){
			$html .= '' . '<select><option>-- No Sites Found --</option> </select>';
		} else {
			$html .= '' .  ' <select name="siteTypeID" id="siteTypeDD"  >
					<option value="0">-- Choose a Site Type --</option> ';
			foreach ($siteTypes as $siteType) {
				$html .= '<option value="' . $siteType->id . '">' . $siteType->name . '</option>';
			}
			$html .= '' .  '</select>  <span class="greenText">Added Site Type: ' .  $siteType->name . '</span>';
		}
		return $html;

	}

	public function add_new_state(Request $request)
	{
		//Validate...i passed siteType instead of name..but it still works..probly not a good idea
		$this->validate($request, [
			'siteType' => 'required|unique:site_types,name,NULL,NULL,customerID,' . $request->customerID . '|max:255',
			'customerID' => 'required',
		]);

		//Add to table
		SiteType::create([
			'name' => $request->siteType,
			'customerID' => $request->customerID,
		]);

		//Reload siteTYpe drop down box
		$customerID = $request->customerID;

		$siteTypes = DB::table('site_types')
			->select('site_types.*')
			->where('site_types.customerID','=',$customerID)
			//->orderby('comments.created_at', 'DSC')
			->get();

		/*	<select name="siteTypeDD" id="siteTypeDD"  >
                <option value="0">-- Choose a Site Type --</option>
                @foreach($siteTypes as $siteType)
                <option value="{{$siteType->id}}">
                {{$siteType->name}}
                </option>
                @endforeach
            </select> */
		$html = '';
		if ($siteTypes == NULL){
			$html .= '' . '<select><option>-- No Sites Found --</option> </select>';
		} else {
			$html .= '' .  ' <select name="siteTypeDD" id="siteTypeDD"  >
					<option value="0">-- Choose a Site Type --</option> ';
			foreach ($siteTypes as $siteType) {
				$html .= '<option value="' . $siteType->id . '">' . $siteType->name . '</option>';
			}
			$html .= '' .  '</select>  <span class="greenText">Added Site Type: ' .  $siteType->name . '</span>';
		}
		return $html;
	}

    /**
     * Display comments for a given $itemId
     *
     * @return Response
     */
    public function show_comments(Request $request)
    {
        //itemId: itemId, genre: genre, userId: userId, role: role,
        $itemId = $request->itemId;
        $userID = $request->userId;
        $genre = $request->genre;
        $role = $request->role;
        $token = $request->_token;

        $comments = Comment::All();
        //$comments = Comment::find(4);
        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select('comments.*','comments.id as commentId','users.id as userId', 'users.name as author',
                'users.imagePath as gravatar')
            ->where('comments.item_id','=',$itemId)
            ->where('comments.type','=',$genre)
            ->orderby('comments.created_at', 'DSC')
            ->get();

        $html = '';
        if ($comments == NULL){
            $html .= 'No Comments Found';
        } else {

            foreach ($comments as $comment) {
                $html .= '<div class="col-sm-12 comments"  id="hiddenComment' . $comment->id . '"</div> " >';
                $html .= $comment->body . '<br><br>';
                $html .= 'By: ' . $comment->author . '<br>Posted: ' . date(("F j, Y"), strtotime($comment->updated_at));
                 if(($comment->userId == $userID) or $role == 'admin' ) {
                     $html .= '<form style="display:inline; float: right"
                            onsubmit="delete_comment(' .  $comment->id  . ')" >
                            <input type="hidden" name="_token" value="' . $token . '">
                            <button type="submit" class="btn btn-danger">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            Delete
                            </button>
                    </form> ';

                }
                $html .= '</div><div class="col-sm-12" ><br/></div>';
            };
        }
        return $html;
    }


    public function add_comment(Request $request)
    {
        if ($request->body == ''){
            return 'Must enter a message';
        } else {


            Comment::create([

                'item_id' => $request->itemId,
                'body' => $request->body,
                'type' => $request->type,
                'user_id' => $request->userId,
            ]);
            return 'new Comment added';
        }
    }

    public function delete_comment(Request $request)
    {
        //
        $id = $request->itemId;
        //$comment = Comment::find($id);
        //$type = $item->type;
        Comment::destroy($id);
        return redirect('./');
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
