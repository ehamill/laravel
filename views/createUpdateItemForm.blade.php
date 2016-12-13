@extends('app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="text-center"> {{ucfirst($action) . ' '. ucfirst($type)}} </h2> <!-- action = create or edit -->
					</div>
					<div class="panel-body">
						<!-- Create Item form -->
						<!-- Display Validation Errors -->
						@include('common.errors')<!-- this file at resources/views/common/errors.blade.php. -->

						<!-- New Item Form example action: create/device..update/phone-->
						<form class="form-horizontal" action="/{{ $action . 'Item'}}  " enctype="multipart/form-data" method="POST" >
							<!--!! csrf_field() !!} -->
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
							<input type="hidden" name="role" value="{{ Auth::user()->role }}">
							<input type="hidden" name="type" value="{{ $type }}">
							<!-- if update, need to pass blogId -->
							@if ( $action == "update" )
								<input type="hidden" name="itemId" value="{{ $itemId }}">
							@endif

							<div class="form-group">
								<label for="title" class="col-sm-2 control-label">Title</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" value="{{$title}}"
										   name="title" placeholder="Enter Title">
								</div>
							</div>

							<div class="form-group">
								<label for="description" class="col-sm-2 control-label">Description</label>
								<div class="col-sm-10">
									<textarea class="form-control" name="description"
											  placeholder="Enter News" rows="3">{{ $description }}</textarea>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-2">
									<p class="help-block">Add an Image.</p>
									<input type="file" name="imagePath">
								</div>
								<div class="col-sm-offset-2 col-sm-4">
									<p class="help-block">Add a Word or Excel document.</p>
									<input type="file" name="documentPath">

								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-2">
									<!-- Show Current image if there is one -->
									@if ($imagePath)
										<img src="../images/{{ $imagePath }}" alt="pic" class="img-rounded">
										<br/><br/>
									@endif
								</div>
							</div>

							<!-- Submit Button -->
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-success">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
										Submit</button>
								</div>
							</div>


						</form>
					</div>


				</div>
			</div>
		</div>
	</div>
@endsection



