@extends('app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Add New Customer</div>

				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')<!-- this file at resources/views/common/errors.blade.php. -->

					<form  action="addCustomer" method="POST"  > <!-- goes to routes, Post::addCustomer, sitesController -->
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="text" name="name" style="width: 250px"
							   placeholder="Enter New Customer Name">
						<input type="text" name="billingAddress" style="width: 250px"
							   placeholder="Enter Billing Address">
						<button type="submit" class="btn btn-success" style="padding: 2px 10px;">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							Add</button>
					</form>


				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function show_add_customer_form() {
		$( "#add_customer_form").removeClass("hidden");
	}

</script>

@endsection
