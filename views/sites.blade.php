@extends('app')

@section('content')

	<style type="text/css">
	th {background-color: #2e6da4}
	.glyphicon:hover {
		cursor: pointer;
		mkargin-top: 2px;
		pkadding: 1px 3px 1px 3px;
		backdground-color: #245269;

	}
	</style>

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="text-center">
					<span style="font-size: 3em">Sites</span>

						<a href="{{ url('/CreateSite') }}" >
							<button data-toggle="tooltip" title="Add Site!" type="button" style="float:right; margin-top: 13px" class="btn btn-success ">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							</button>
						</a>
					</div>
				</div>
				<div class="panel-heading" style="background-color: #46b8da; font-size: medium; ">
						<span style="color: white; font-size: large">Filter:</span>
						<select name="customerDDfilter" id="customerDDfilter" onchange="filterSites()">
						<option value="">-- Choose a Customer --</option> <!-- NOTE: for required, value must be "" -->
						@foreach($customers as $customer)
							<option value="{{$customer->id}}">
								{{$customer->name}}
							</option>
						@endforeach
						</select>

						<span style="color: white"> Site Number: </span>
						<input type="text" style="width: 50px" name="siteNumberFilter" id="siteNumberFilter" onkeyup="filterSites()" />

						<select name="countryDDfilter" id="countryDDfilter" onchange="loadStates(this.value)" >
						<option value="">-- Choose a Country --</option> <!-- NOTE: for required, value must be "" -->
						@foreach($countries as $country)
							<option value="{{$country->id}}">
								{{$country->CountryName}}
							</option>
						@endforeach
						</select>
						<span id="hiddenStatesDD"></span>
						<select id="stateDDfilter" >
						<option value="">-- Choose a State --</option> <!-- NOTE: for required, value must be "" -->
						</select> &nbsp;&nbsp;
						<span style="color: white; font-size: large" class="glyphicon glyphicon-filter"
						  data-toggle="tooltip" title="Sumbit Filter!!" onclick="filterSites()" aria-hidden="true"></span>
						</div>
						<div class="panel-body">

						<!-- SHOW SITES -->
						<div id="ajaxSites"></div>
						<div id="sites">
						@foreach($sites as $site)
							<div class="col-md-3" >
								<table style=" width: 90%; text-align: center; margin-bottom: 10px; border: solid; border-color: #999999">
									<tr>
										<th style=" text-align: center; color: white">
											{{ $site->customerName }}<br>
											<span style=" text-align: center;" class="badge">{{ $site->siteNumber }}</span>
										</th>
									</tr>
									<tr>
										<td >
											{{ $site->siteTypeName }}<br>
											{{ $site->address }}<br/>
											{{ $site->cityName }}, {{ $site->StateAbbreviation }} {{$site->zip}}
											<br>
											{{ $site->countryName }}<br>
											<div class="btn-group" role="group" >
												<a href="{{ url('editSite/' . $site->siteID) }}" >
													<button type="button"  class="btn-xs btn-success">Edit</button>
												</a>
												<a href="{{ url('deleteSite/' . $site->siteID) }}" >
													<button type="button" class="btn-xs btn-danger">Delete</button>
												</a>
											</div>

										</td>
									</tr>
								</table>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	<script type="text/javascript">

	function filterSites(){
		var customerID = $("#customerDDfilter").val();
		var siteNumber = $("#siteNumberFilter").val();
		var countryID = $("#countryDDfilter").val();
		var	stateID = $("#stateDD").val();
		//alert ('customerid: ' + customerID + ' siteNumber: ' + siteNumber + ' countryid: ' + countryID + ' stateID: ' + stateID);
		$.ajax({    //create an ajax request to load_page.php
			method: "POST",
			url: "filter_sites", //Goes to routes.php, and to ajaxController, Route::POST('load_site_types', 'AjaxControllerATload_site_types');
			data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
				customerID: customerID, siteNumber: siteNumber,countryID: countryID, stateID: stateID,
			},
			dataType: "html",   //expect html to be returned
			success: function (data) {
				//alert('success');
				$("#sites").hide();
				$("#ajaxSites").html(data);
				//alert(data);
			},
			error: function(){
				alert('failed to filter customers');
			}
		});
	}

	function loadStates($countryID){
		//	alert("CountryID: " + $countryID)
		var countryID = $countryID;

		$.ajax({    //create an ajax request to load_page.php
			method: "POST",
			url: "load_states2", //Goes to routes.php, and to ajaxController, Route::POST('load_site_types', 'AjaxControllerATload_site_types');
			data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
				countryID: countryID,
			},
			dataType: "html",   //expect html to be returned
			success: function (data) {
				//alert('success');
				$("#stateDDfilter").hide();
				$("#hiddenStatesDD").html(data);
				filterSites();
			},
			error: function(){
				alert('failed to load states');
			}
		});
	}

</script>

@endsection
