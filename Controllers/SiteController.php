<?php namespace App\Http\Controllers;

use App\Country;
use App\Customer;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Site;
use App\SiteType;
use App\State;
use App\City;
use DB;
use Illuminate\Http\Request;

class SiteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function index()
    {
        $sites = DB::table('sites')
            ->join('customers', 'sites.customerID', '=', 'customers.id')
            ->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
            ->join('countries', 'sites.countryID', '=', 'countries.id')
            ->join('states', 'sites.stateID', '=', 'states.id')
            ->join('cities', 'sites.cityID', '=', 'cities.id')
            ->select('*', 'customers.name as customerName','countries.CountryName as countryName',
                'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
            ->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
            ->get();
        $customers = Customer::all(); //to load the customers drop down box
        $countries = Country::all();  // to load the countries drop down box
        return view('sites', ['sites' => $sites, 'customers' => $customers, 'countries' => $countries]);

    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
public function create()
{

    $customers = Customer::All();
    $countries = Country::All();
    $states = State::All();
    $action = 'Create';
    return view('addSite', ['action' => $action, 'customers' => $customers, 'countries' => $countries,
        'states' => $states,]);
}


    //Add new country
    public function addCountry(Request $request)
    {
        $this->validate($request, [
            'countryName' => 'required|unique:Countries,CountryName|max:55',
            'countryAbbreviation' => 'required|unique:Countries,CountryAbbreviation|max:2',
        ]);

        //Add to table
        Country::create([
            'CountryName' => $request->countryName,
            'CountryAbbreviation' => $request->countryAbbreviation
        ]);

        return redirect()->action('SiteController@create');

    }

    public function add_new_state(Request $request)
    {
        $this->validate($request, [
            'countryID' => 'required',
            'stateName' => 'required|unique:States,StateName,NULL,NULL,countryID,' . $request->countryID . '|max:55',
            'stateAbbreviation' => 'required|unique:States,StateAbbreviation,NULL,NULL,countryID,' . $request->countryID .'|max:2',
        ]);

        //Add to table
        State::create([
            'countryID' => $request->countryID,
            'StateName' => $request->stateName,
            'StateAbbreviation' => $request->stateAbbreviation
        ]);

    }

    public function check_site_number(Request $request)
    {

        $this->validate($request, [
            'customerID' => 'required',
            'siteNumber' => 'required|unique:Sites,siteNumber,NULL,NULL,customerID,' . $request->customerID . '|max:55',
        ]);

        //if we get this far, siteNumber doesn't already exist
        $html = '' . '<span class="text-success">Site Number Available!</span>';
        return $html;
    }


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
                'name' => 'required|unique:cities,name,NULL,NULL,countryID,' . $request->countryID . ',stateID,' . $request->stateID . '|max:255',
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

        $customers = Customer::All();
        $countries = Country::All();
        $states = State::All();
        $cities = City::All();
        $action = 'create';
        return view('addSite', ['action' => $action, 'customers' => $customers, 'countries' => $countries,
            'states' => $states,'cities' => $cities]);
    }



	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
    public function store(Request $request)
    {
        $this->validate($request, [
            'customerID'=> 'required',
            'siteNumber' => 'required|unique:sites,siteNumber,NULL,NULL,customerID,' . $request->customerID,
            'siteTypeID' => 'required',
            'countryID' => 'required',
            'stateID' => 'required',
            'cityName' => 'required',
            'address' => 'required',
            'zip' => 'required',
        ]);
        //Get cityName, find the id, OR add city to DB, then get the new id
        $cityName = $request->cityName;
        //$cityName = "Madison";
        $cityID = '';
        //Get id of city
        $city = DB::table('cities')
            ->select('cities.id')
            ->where('cities.name', '=', $cityName)
            ->where('cities.stateID', '=', $request->stateID)
            ->where('cities.countryID', '=', $request->countryID)
            ->first();
        if ($city != ''){$cityID = $city->id;}
        //if city not found, ie Null, create a new city and assign the cityID
        if($cityID == ''){
            $newCity = City::create([
                'name' => $cityName,
                'countryID' => $request->countryID,
                'stateID' => $request->stateID,
            ]);
            $cityID = $newCity->id;
        }

        //Add to table
        Site::create([
            'customerID' => $request->customerID,
            'siteNumber' => $request->siteNumber,
            'siteTypeID' => $request->siteTypeID,
            'countryID' => $request->countryID,
            'stateID' => $request->stateID,
            'cityID' => $cityID,
            'address' => $request->address,
            'zip' => $request->zip,
        ]);

        return redirect()->action('SiteController@index');
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
        $action = 'Edit';
        $site = Site::Find($id);
        $customers = Customer::All();
        //Only get siteTypes for this site's customer
        $siteTypes = SiteType::where('customerID', $site->customerID)
        ->orderBy('name', 'asc')
        ->get();
        $countries = Country::All();
        //Don't want all the states, just states in this site's country
        $countryID = $site->countryID;
        $states = State::where('countryID', $countryID)
            ->orderBy('StateName', 'asc')
            ->get();

        $siteTypeID = $site->siteTypeID;
        $city = City::Find($site->cityID);
        $cityName = $city->name;
        return view('addSite', ['action' => $action, 'site' => $site,'customers' => $customers, 'countries' => $countries,
            'states' => $states, 'siteTypes' => $siteTypes, 'siteTypeID' => $siteTypeID, 'cityName' => $cityName]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        //
        $this->validate($request, [
            'customerID'=> 'required',
            'siteNumber' => 'required|unique:sites,siteNumber,' . $request->siteID . ',id,customerID,' . $request->customerID,
            'siteTypeID' => 'required',
            'countryID' => 'required',
            'stateID' => 'required',
            'cityName' => 'required',
            'address' => 'required',
            'zip' => 'required',
        ]);
        //Get cityName, find the id, OR add city to DB, then get the new id
        $cityName = $request->cityName;
        //$cityName = "Madison";
        $cityID = '';
        //Get id of city
        $city = DB::table('cities')
            ->select('cities.id')
            ->where('cities.name', '=', $cityName)
            ->where('cities.stateID', '=', $request->stateID)
            ->where('cities.countryID', '=', $request->countryID)
            ->first();
        if ($city != ''){$cityID = $city->id;}
        //if city not found, ie Null, create a new city and assign the cityID
        if($cityID == ''){
            $newCity = City::create([
                'name' => $cityName,
                'countryID' => $request->countryID,
                'stateID' => $request->stateID,
            ]);
            $cityID = $newCity->id;
        }

        //Everything is validated, get Site and update it
        $site = Site::find($request->siteID);
        $site->customerID = $request->customerID;
        $site->siteNumber = $request->siteNumber;
        $site->siteTypeID = $request->siteTypeID;
        $site->countryID = $request->countryID;
        $site->stateID = $request->stateID;
        $site->cityID = $cityID;
        $site->address = $request->address;
        $site->zip = $request->zip;
        $site->save();

        return redirect()->action('SiteController@index');

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
        return redirect()->action('SiteController@index');

    }

	public function filter_sites(Request $request)
	{
		$customerID = $request->customerID;
		$siteNumber = $request->siteNumber;
		$countryID = $request->countryID;
		$stateID = $request->stateID;


		if     ( $customerID == '' && $siteNumber == '' && $countryID == '' && $stateID == ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID == '' && $siteNumber == '' && $countryID == '' && $stateID != ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.stateID', '=', $stateID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID == '' && $siteNumber == '' && $countryID != '' && $stateID == ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.countryID', '=', $countryID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID == '' && $siteNumber == '' && $countryID != '' && $stateID != ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.countryID', '=', $countryID)
				->where('sites.stateID', '=', $stateID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID == '' && $siteNumber != '' && $countryID == '' && $stateID == ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.siteNumber', 'like', '%' . $siteNumber . '%')
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID == '' && $siteNumber != '' && $countryID == '' && $stateID != ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.siteNumber', 'like', '%' . $siteNumber . '%')
				->where('sites.stateID', '=', $stateID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID == '' && $siteNumber != '' && $countryID != '' && $stateID == ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.siteNumber', 'like', '%' . $siteNumber . '%')
				->where('sites.countryID', '=', $countryID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID == '' && $siteNumber != '' && $countryID != '' && $stateID != ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.siteNumber', 'like', '%' . $siteNumber . '%')
				->where('sites.countryID', '=', $countryID)
				->where('sites.stateID', '=', $stateID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID != '' && $siteNumber == '' && $countryID == '' && $stateID == ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.customerID', '=', $customerID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID != '' && $siteNumber == '' && $countryID == '' && $stateID != ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.customerID', '=', $customerID)
				->where('sites.stateID', '=', $stateID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID != '' && $siteNumber == '' && $countryID != '' && $stateID == ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.customerID', '=', $customerID)
				->where('sites.countryID', '=', $countryID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID != '' && $siteNumber == '' && $countryID != '' && $stateID != ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.customerID', '=', $customerID)
				->where('sites.countryID', '=', $countryID)
				->where('sites.stateID', '=', $stateID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID != '' && $siteNumber != '' && $countryID == '' && $stateID == ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.customerID', '=', $customerID)
				->where('sites.siteNumber', 'like', '%' . $siteNumber . '%')
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID != '' && $siteNumber != '' && $countryID == '' && $stateID != ''){
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.customerID', '=', $customerID)
				->where('sites.siteNumber', 'like', '%' . $siteNumber . '%')
				->where('sites.stateID', '=', $stateID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID != '' && $siteNumber != '' && $countryID != '' && $stateID == '') {
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.customerID', '=', $customerID)
				->where('sites.siteNumber', 'like', '%' . $siteNumber . '%')
				->where('sites.countryID', '=', $countryID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		}
		else if( $customerID != '' && $siteNumber != '' && $countryID != '' && $stateID != '')
		{
			$sites = DB::table('sites')
				->join('customers', 'sites.customerID', '=', 'customers.id')
				->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
				->join('countries', 'sites.countryID', '=', 'countries.id')
				->join('states', 'sites.stateID', '=', 'states.id')
				->join('cities', 'sites.cityID', '=', 'cities.id')
				->select('*', 'customers.name as customerName', 'countries.CountryName as countryName',
					'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
				->where('sites.customerID', '=', $customerID)
				->where('sites.siteNumber', 'like', '%' . $siteNumber . '%')
				->where('sites.countryID', '=', $countryID)
				->where('sites.stateID', '=', $stateID)
				->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
				->get();
		} else {
			$html = 'Error';
			return $html;
		}

	//I wanted to make the filter ajax, instead of a post. WOW, what a nightmare. With 4 filter options,
	// there are 16 different sql queries.
	//So how do you think of all the different options?
	//Answer: think binary. 0000, 0001, 0010, 0011, 0100, etc (in decimal this is 0,1,2,3,4 )
	// Thus == '', or where input is Null, it's a zero, else it is a 1.
	//Thus 0000 = if ( $customerID == '' && $siteNumber == '' && $countryID == '' && $stateID == '')
	//Thus 0001 = if ( $customerID == '' && $siteNumber == '' && $countryID == '' && $stateID != '')
	//Thus 0010 = if if ( $customerID == '' && $siteNumber == '' && $countryID != '' && $stateID == '')
	/*     CRAZY CODE!!!!!!
	if     ( $customerID == '' && $siteNumber == '' && $countryID == '' && $stateID == '')
	else if( $customerID == '' && $siteNumber == '' && $countryID == '' && $stateID != '')
	else if( $customerID == '' && $siteNumber == '' && $countryID != '' && $stateID == '')
	else if( $customerID == '' && $siteNumber == '' && $countryID != '' && $stateID != '')
	else if( $customerID == '' && $siteNumber != '' && $countryID == '' && $stateID == '')
	else if( $customerID == '' && $siteNumber != '' && $countryID == '' && $stateID != '')
	else if( $customerID == '' && $siteNumber != '' && $countryID != '' && $stateID == '')
	else if( $customerID == '' && $siteNumber != '' && $countryID != '' && $stateID != '')
	else if( $customerID != '' && $siteNumber == '' && $countryID == '' && $stateID == '')
	else if( $customerID != '' && $siteNumber == '' && $countryID == '' && $stateID != '')
	else if( $customerID != '' && $siteNumber == '' && $countryID != '' && $stateID == '')
	else if( $customerID != '' && $siteNumber == '' && $countryID != '' && $stateID != '')
	else if( $customerID != '' && $siteNumber != '' && $countryID == '' && $stateID == '')
	else if( $customerID != '' && $siteNumber != '' && $countryID == '' && $stateID != '')
	else if( $customerID != '' && $siteNumber != '' && $countryID != '' && $stateID == '')
	else if( $customerID != '' && $siteNumber != '' && $countryID != '' && $stateID != '')


	Won't bore you with all the queries, but here is the first and the last
	if     ( $customerID == '' && $siteNumber == '' && $countryID == '' && $stateID == ''){
	$sites = Site::all();
	}
	else if( $customerID != '' && $siteNumber != '' && $countryID != '' && $stateID != '')
	{
	$sites = DB::table('sites')
		->join('customers', 'sites.customerID', '=', 'customers.id')
		->join('site_types', 'sites.siteTypeID', '=', 'site_types.id')
		->join('countries', 'sites.countryID', '=', 'countries.id')
		->join('states', 'sites.stateID', '=', 'states.id')
		->join('cities', 'sites.cityID', '=', 'cities.id')
		->select('*', 'customers.name as customerName','countries.CountryName as countryName',
			'sites.id as siteID', 'site_types.name as siteTypeName', 'cities.name as cityName')
		->where('customerID','=',$customerID)
		->where('siteNumber','=',$siteNumber)
		->where('countryID','=',$countryID)
		->where('stateID','=',$stateID)
		->orderBy('customers.name', 'ASC')->orderBy('siteNumber', 'ASC')
		->get();
	}

							/*
		 *
		 */

        $html = '';
        if ($sites != NULL){
            foreach( $sites as $site){
                $html .= '' . '<div class="col-md-3" >
                                <table style=" width: 90%; text-align: center; margin-bottom: 10px; border: solid; border-color: #999999">
                                    <tr>
                                        <th style=" text-align: center; color: white">' .
                                             $site->customerName  . '<br>' .
                                            '<span style=" text-align: center;" class="badge"> ' . $site->siteNumber . '</span>' .
                                        '</th>
                                    </tr>
                                    <tr>
                                        <td >' .
                                            $site->siteTypeName . '<br>' .
                                             $site->address . '<br>' .
                                             $site->cityName . ', ' . $site->StateAbbreviation .  $site->zip . '<br>' .
                                             $site->countryName . '<br>' .
                                            '<div class="btn-group" role="group" >
                                                <a href="editSite/' . $site->siteID . '" >
                                                    <button type="button"  class="btn-xs btn-success">Edit</button>
                                                </a>
                                                <a href="deleteSite/' . $site->siteID . '" >
                                                    <button type="button" class="btn-xs btn-danger">Delete</button>
                                                </a>
                                            </div>
        
                                        </td>
                                    </tr>
                                </table>
                            </div>';
            }
        } else { $html = 'No sites found';}
        return $html;
	}


}
