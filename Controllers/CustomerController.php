<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer; // Allows use of Customer::[All() or Find()]
use App\Country;
use App\State;
use App\City;

use Illuminate\Http\Request;

class CustomerController extends Controller {

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
		return view('addCustomer');

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//
		$this->validate($request, [
			'name' => 'required|unique:customers|max:255', //"name" must be unique in the customers Table..assumes you mean name column
			//'name' => 'required|unique:customers,name|max:255',   // This does exact same thing as line above
			//'customerName' => 'required|unique:customers|max:255',   // Error...laravel will look for customerName field
			////'customerName' => 'required|unique:customers,name|max:255',   // OK..no error, laravel now knows you looking for unique name field
		]);

		$customer = Customer::create([
			'name' => $request->name,
			'billingAddress'=> $request->billingAddress,
		]);

        $customers = Customer::All();
        $countries = Country::All();
        $states = State::All();
        $cities = City::All();
        $action = 'Create';
        return view('addSite', ['action' => $action, 'customers' => $customers, 'countries' => $countries,
            'states' => $states,'cities' => $cities]);


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
