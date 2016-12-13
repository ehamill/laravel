@extends('app')

@section('content')
	<style type="text/css">
	label {width: 20%; text-align: right; padding: 0 5px 7px 0 }
	</style>


	<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="text-center"> {{$action}} Site </h2>
				</div>

				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')<!-- this file at resources/views/common/errors.blade.php. -->



					<!-- New Site Form -->
					<form class="form-horizontal" action="/{{ $action}}Site" method="POST" >
						<!-- when submitted goes to  routes.php and hits, ex: Route::post('/{create || update}Site',
						'SiteController.. create->store or update->edit');-->
						<!--!! csrf_field() !!} -->
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						@if($action == 'Edit') <input type="hidden" name="siteID" value="{{ $site->id }}"> @endif

						<div class="form-group">
							<div class="col-md-10">
								<label for="customerDD">Customer:</label>
								<select required name="customerID" id="customerDD" onchange="loadSiteType(this.value)">
									<option value="">-- Choose a Customer --</option> <!-- NOTE: for required, value must be "" -->
									@foreach($customers as $customer)
										<option value="{{$customer->id}}"
											@if($action == 'Edit')
												@if ($site->customerID == $customer->id)
													selected
												@endif
											@endif
										>
											{{$customer->name}}
										</option>
									@endforeach
								</select>
								&nbsp;&nbsp;
								<a href="{{ url('/addCustomer') }}">Add New Customer</a> Old school style. Go to new page.
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-10">
								<label for="siteTypeID"  style="padding-top: 0; padding-bottom: 7px" >
									Site Type:</label>
									<!-- Hidden SiteTypeDrop Down -->
									<span id="siteTypes" ></span>

									@if($action == 'Edit')
										<select name="siteTypeID" id="siteTypeDD"  >
											<option value="">-- Choose a Site Type --</option>
											@foreach($siteTypes as $siteType)
												<option value="{{$siteType->id}}"
													@if ($site->siteTypeID == $siteType->id)
															selected
													@endif
												>
													{{$siteType->name}}
												</option>
											@endforeach
										</select>
									<a href="#0" id="addNewSiteTypeLink"
									   onclick="show_add_site_type_form()">Add New Site Type</a>&nbsp;&nbsp;
									@endif
									&nbsp;&nbsp;
									<a href="#0" id="addNewSiteTypeLink" style="display: none"
									   onclick="show_add_site_type_form()">Add New Site Type</a>&nbsp;&nbsp;
									<span id="mustSelectCustomer" class="text-danger" style="display: none">
										Error: Must Select Customer First</span>

									<input type="text" class="addSiteTypeForm" id="newSiteType" name="newSiteType" style="display: none ; width: 150px"
										   placeholder="Enter New SiteType"/>
									<button  onclick="submitSiteType()" data-toggle="tooltip" title="Add Site Type" type="button"
											style="display: none;" class="btn-xs btn-success addSiteTypeForm">
										<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									</button>
									<span id="siteAlreadyExists" class="text-danger" style="display: none">
										Error: Site Already Exists</span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-10">
								<label for="siteNumber" > Site Number:</label>
									<input type="text" @if($action == "Create") onblur="checkSiteNumber(this.value)" @endif
										id="siteNumber" name="siteNumber" required placeholder="Enter Site Number"
										    @if($action == 'Edit') value="{{$site->siteNumber}}" @endif/>
								<span id="hiddenSiteNumberError" ></span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-10">
								<label for="countryDD">Country:</label>
									<select name="countryID" id="countryDD" onchange="loadStates(this.value)">
										<option value="">-- Choose a Country --</option>
										@foreach($countries as $country)
											<option value="{{$country->id}}"
												@if($action == 'Edit')
													@if ($site->countryID == $country->id)
														selected
													@endif
												@endif
											>
												{{$country->CountryName}}
											</option>
										@endforeach
									</select>
									&nbsp;&nbsp;
									<a href="#addCountry">Add New Country</a>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-10">
								<label for="stateDD">State:</label>
									<!-- Hidden States Drop Down -->
									<span id="hiddenStateDD" ></span>

									@if($action == 'Edit')
										<select name="stateID" id="stateDD" onchange="loadCities(this.value)"  >
											@foreach($states as $state)
												<option value="{{$state->id}}"
													@if ($site->stateID == $state->id)
														selected
													@endif
												>
													{{$state->StateName}}
												</option>
											@endforeach
										</select>
									@endif

									&nbsp;&nbsp;
									<button type="button" class="btn btn-info btn-sm" data-toggle="modal"
											data-target="#myModal">Add New State by Modal</button>
							</div>
						</div>

							<div class="form-group">
								<div class="col-md-10">
									<label for="cities" >City:</label>
									@if($action == 'Edit')
										<input type="text" name="cityName" value="{{$cityName}}">
									@else
										<span id="hiddenCities"></span>
									@endif
								</div>
							</div>
						<div class="form-group">
							<div class="col-sm-10">
								<label for="address"> Address: </label>
									<input type="text" id="address" name="address" required placeholder="Enter Address"
										@if($action == 'Edit') value="{{$site->address}}"@endif />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-10">
								<label for="zip"> Zip Code:</label>
								<input type="text" id="zip" name="zip" required placeholder="Enter Zip Code"
										   @if($action == 'Edit') value="{{$site->zip}}"@endif/>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-10">
								<label for="submit"></label>
								<button type="submit" class="btn btn-success">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									Submit</button>
							</div>
						</div>

					</form>
					</div>


				<!--     ADD NEW SITE TYPE, STATE, CITY, ETC-->
				<!--     ADD NEW SITE TYPE, STATE, CITY, ETC-->				<!--     ADD NEW SITE TYPE, STATE, CITY, ETC-->

				<div class="panel-heading">Add New Country</div>
					<div class="panel-body">

						<form class="form-horizontal" action="/addCountry" method="POST" >
							<!-- when submitted goes to  routes.php and hits, ex: Route::post('/addCountry',
								'SiteController.. to addCountry Function;-->
							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<div class="form-group">
								<div class="col-sm-10">
									<label for="title"  style="padding-top: 0; padding-bottom: 7px" > Add New Country:
									<input type="text" id="addCountry" name="countryName" required placeholder="Enter Country Name"/>
									</label>
								</div>
								<div class="col-sm-10">
									<label for="title"  style="padding-top: 0; padding-bottom: 7px" > Abbreviation:
									<input type="text" name="countryAbbreviation" required placeholder="Enter Abbreviation" />
									</label>
								</div>
								<div class="col-sm-10">
									<button type="submit" class="btn btn-success">
										<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
										Submit</button>

								</div>
							</div>
						</form>

					</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header" style="background-color: #2e6da4; color: white">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Add New State</h4>
			</div>
			<div class="modal-body text-center">
				<!-- HIDDEN STUFF -->
				<h2 id="hiddenStateAddedMessage" class="text-success" style="display: none;">New State Added!!</h2>
				<h3 id="newStateFailed" class="text-danger" style="display: none;">
					New State Name and/or Abbreviation already exists.</h3>
				<h3 id="failedValidation" class="text-danger" style="display: none;"></h3>

				<!-- Ajax "FORM" -->
				<label for="countryID2">Country: </label>
				<select required name="countryID2" id="countryID2" >
					<option value="">-- Choose a Country --</option> <!-- NOTE: for required, value must be "" -->
					@foreach($countries as $country)
						<option value="{{$country->id}}">
							{{$country->CountryName}}
						</option>
					@endforeach
				</select>
				<br/>
				<label for="stateName"> State Name:</label>
				<input type="text" id="stateName" name="stateName" required placeholder="Enter State Name"/>
				<br/>
				<label for="stateAbbreviation"> Abbreviation:</label>
				<input type="text" autocomplete="off" id="stateAbbreviation" name="stateAbbreviation" required placeholder="Enter Abbreviation" />

				<br/>
				<button onclick="submitNewState()" class="btn btn-success">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
						Submit</button>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>


				<!-- END OF PAGE--->
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	//After customerDropDown selected, this fn loads site types drop down
	function loadSiteType($customerID) {
		$("#mustSelectCustomer").hide();
		$( "#addNewSiteTypeLink").show();

		@if ($action == "Edit") $("#siteTypeDD").hide(); @endif//If in edit mode, need to hide first drop down
		//	alert("CustomerID: " + $customerID)
		var customerID = $customerID;

		$.ajax({    //create an ajax request to load_page.php
			method: "POST",
			url: "load_site_types", //Goes to routes.php, and to ajaxController, Route::POST('load_site_types', 'AjaxControllerATload_site_types');
			data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
				customerID: customerID,
			},
			dataType: "html",   //expect html to be returned
			success: function (data) {
				//alert('success');
				$("#siteTypes").html(data);
				//alert(data);
			},
			error: function(){
				alert('failed to load Site Types');
			}
		});
	}

	//If user clicks add site_type: Show input text box and link to add site_type by ajax
	function show_add_site_type_form() {
		$( ".addSiteTypeForm").show();
	}


	function show_add_city_input() {
		if($( "#customerDD").val() == 0 ) { //gets selected value of country drop down
			//If a Country or State from drop down not selected, show error text.
			$( "#mustSelectCountryState").show();
		} else if ($( ("#stateDD").val() == 0 ).val() == 0) {
			$( "#mustSelectCountryState").show();
		} else {
		$( "#mustSelectCountryState").hide();
			$( ".addCityInput").show();
		}
	}


	function submitSiteType(){
		var siteType = $( "#newSiteType").val(); //Get siteType input
		var customerID = $( "#customerDD").val(); //get customerID selected value
		//alert("siteType:" + siteType + " custID: " + customerID);
		if (siteType == ""){
			alert('Must Enter a site type!');
		} else {
			$.ajax({    //create an ajax request to load_page.php
				method: "POST",
				url: "add_site_type", //Goes to routes.php, and to ajaxController, Route::POST('load_site_types', 'AjaxControllerATload_site_types');
				data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
					siteType: siteType, customerID: customerID,
				},
				dataType: "html",   //expect html to be returned
				success: function (data) {
					//alert('success');
					$( "#siteTypeDD").hide();
					document.getElementById("newSiteType").value = ""; //Clear input text box
					$("#siteTypes").html(data);
					//alert(data);
					$( ".addSiteTypeForm").hide();
					$( "#siteAlreadyExists").hide();
				},
				error: function(){
					//if we get here, validation failed
					alert('Site Type already exists');
					$( "#siteAlreadyExists").show();
				}
			});
		}
	}

	//After Site Number entered, check for duplicate site number (for the customer) and show error message if site
	// number already exists for that customer.
function checkSiteNumber($siteNumber){
	var siteNumber = $siteNumber; //get customerID selected value
	var customerID = $( "#customerDD").val(); //get customerID selected value
//		alert("siteNumber: class=" + siteNumber + "custID: " + customerID);

	$( "#hiddenSiteNumberError").hide();
	$.ajax({    //create an ajax request to load_page.php
		method: "POST",
		url: "check_site_number", //Goes to routes.php, and to ajaxController, Route::POST('load_site_types', 'AjaxControllerATload_site_types');
		data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
			siteNumber: siteNumber, customerID: customerID,
		},
		dataType: "html",   //expect html to be returned
		success: function (data) {
			//alert('success at site number');
			//if siteNumber looks good = "Site Number Available", else returns longer error message
			$( "#hiddenSiteNumberError").show();
			$("#hiddenSiteNumberError").html(data);
		},
		error: function(){
			alert('failed site number check');
			//if we get here, validation failed
			$( "#hiddenSiteNumberError").show();
			var html =  '<span style="background-color: yellow" class="text-danger">Site Number <mark>' + siteNumber + '</mark> already exists!';
			document.getElementById("hiddenSiteNumberError").innerHTML = html;
			document.getElementById("siteNumber").value = "";
		}
	});


	}

	//After countryDropDown selected, this fn loads states drop down
	function loadStates($countryID) {

		//	alert("CountryID: " + $countryID)
		var countryID = $countryID;

		@if ($action == "Edit") $("#stateDD").hide(); @endif//If in edit mode, need to hide first drop down

		$.ajax({    //create an ajax request to load_page.php
			method: "POST",
			url: "load_states", //Goes to routes.php, and to ajaxController, Route::POST('load_site_types', 'AjaxControllerATload_site_types');
			data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
				countryID: countryID,
			},
			dataType: "html",   //expect html to be returned
			success: function (data) {
				//alert('success');
				$("#hiddenStateDD").html(data);
				//alert(data);
			},
			error: function(){
				alert('failed to load states');
			}
		});
	}

	//After StateDropDown selected, this fn loads cities drop down
	function loadCities($stateID) {
		$( "#addNewCityLink").show();
		var countryID = $( "#countryDD").val(); //get countryID selected value
		var stateID = $stateID;
//		alert("CountryID: " + countryID + " StateID: " + stateID);

		$.ajax({
			method: "POST",
			url: "load_cities", //Goes to routes.php, and to ajaxController, Route::POST('load_site_types', 'AjaxControllerATload_site_types');
			data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
				countryID: countryID, stateID: stateID,
			},
			dataType: "html",   //expect html to be returned
			success: function (data) {
				//alert('success');
				$("#hiddenCities").html(data);
			},
			error: function(){
				//alert('failed to load cities');
			}
		});
	}

	//After countryDropDown selected, this fn loads states drop down
	function loadStatesModal($countryID) {

		//	alert("CountryID: " + $countryID)
		var countryID = $countryID;

		$.ajax({    //create an ajax request to load_page.php
			method: "POST",
			url: "load_states", //Goes to routes.php, and to ajaxController, Route::POST('load_site_types', 'AjaxControllerATload_site_types');
			data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
				countryID: countryID,
			},
			dataType: "html",   //expect html to be returned
			success: function (data) {
				//alert('success');
				$("#stateDD3").html(data);
				//alert(data);
			},
			error: function(){
				alert('failed to load states');
			}
		});
	}

function submitNewState(){
	var countryID = $( "#countryID2").val(); //get countryID selected value from Modal
	var stateName = $( "#stateName").val();
	var stateAbbreviation = $( "#stateAbbreviation").val();
	//alert("countryID:" + countryID + " stateNmae: " + stateName + " Abbrev: " + stateAbbreviation);
	$("#newStateFailed").hide();
	$("#failedValidation").hide();
	if (!countryID ) {
		$("#failedValidation").show();
		document.getElementById("failedValidation").innerHTML = "Please select a country.";
	} else if(!stateName){
		$("#failedValidation").show();
		document.getElementById("failedValidation").innerHTML = "Please enter a State Name.";
	} else if(!stateAbbreviation){
		$("#failedValidation").show();
		document.getElementById("failedValidation").innerHTML = "Please enter an Abbreviation.";
	}

	else{
		$.ajax({    //create an ajax request to load_page.php
			method: "POST",
			url: "add_new_state", //Goes to routes.php, and to ajaxController, Route::POST('load_site_types', 'AjaxControllerATload_site_types');
			data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
				countryID: countryID, stateName: stateName, stateAbbreviation: stateAbbreviation,
			},
			dataType: "html",   //expect html to be returned
			success: function (data) {
				//alert('success at new state added');
				loadStates(countryID);
				document.getElementById("countryID2").value = "";
				document.getElementById("stateName").value = "";
				document.getElementById("stateAbbreviation").value = ""; //Clear input text box
				$("#hiddenStateAddedMessage").show();

				//$("#states").html(data);//load span element "states" with new states drop down
				//alert(data);
			},
			error: function(){
				//if we get here, validation failed
				$("#newStateFailed").show();
				alert('New State Name and/or Abbreviation already exists.');
			}
		});
	}
	}


</script>

@endsection
