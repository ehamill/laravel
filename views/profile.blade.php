@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">

				<!-- HEADING -->
				<div class="panel-heading text-center"> <h2>Update Profile </h2></div>
				@if (Auth::user()->role == "admin")
					<div class="panel-heading text-center">
						<a  href="../showProfile/adminPage" class="btn btn-warning active" role="button">
							Admin Page</a> &nbsp;&nbsp;&nbsp;&nbsp;
						<a  href="../showProfile/pending" class="btn btn-warning active" role="button">
							Pending Posts ({{ \App\Item::get_pending_count() }})</a>
					</div>
				@endif
				<!-- ERRORS -->
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<!-- PROFILE UPDATE -->
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST"
						  action="../updateProfile">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ $user->name }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ $user->email }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Job Title</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="jobTitle" value="{{ $user->jobTitle }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Add/Update your Image:</label>
							<div class="col-md-6">
								<input type="file" class="form-control" name="imagePath" value="{{ $user->imagePath }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Reset Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Update
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
