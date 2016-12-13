@extends('app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="text-center"> Edit Code Blog </h2>
					</div>
					<div class="panel-body">
						<!-- Create Item form -->
						<!-- Display Validation Errors -->
						@include('common.errors')<!-- this file at resources/views/common/errors.blade.php. -->

						<!-- New Item Form example action: create/device..update/phone-->
						<form class="form-horizontal" action="{{ url('Tutorial/edit/'. $pageName) }}" enctype="multipart/form-data" method="POST" >
							<!--!! csrf_field() !!} -->
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="pageName" value="{{ $pageName }}">
							<input type="hidden" name="id" value="{{ $code_blog->id }}">

							<div class="form-group">
								<label for="pageName" class="col-sm-2 control-label">Priority</label>
								<div class="col-sm-2">
									<input type="integer" autocomplete="off" required name="priority"
										   class="form-control" value="{{ $code_blog->priority }}" >
								</div>
							</div>

							<div class="form-group">
								<label for="title" class="col-sm-2 control-label">Title</label>
								<div class="col-sm-10">
									<input type="text" autocomplete="off" required class="form-control" name="title"
										   value="{{ $code_blog->title }}" placeholder="Enter Title..">
								</div>
							</div>

							<div class="form-group">
								<label for="description" class="col-sm-2 control-label">Description</label>
								<div class="col-sm-10">
									<textarea class="form-control" name="description" rows="10">{{ $code_blog->description }}</textarea>
								</div>
							</div>

							<div class="form-group">
								<label for="code" class="col-sm-2 control-label">Code</label>
								<div class="col-sm-10">
									<textarea class="form-control" name="code"
											  placeholder="Enter Code" rows="20">{{ $code_blog->code }}</textarea>
								</div>
							</div>


							<div class="form-group">
								<label for="description2" class="col-sm-2 control-label">Description2</label>
								<div class="col-sm-10">
									<textarea class="form-control" name="description2" rows="10">{{ $code_blog->description2 }}</textarea>
								</div>
							</div>

							<div class="form-group">
								<label for="code2" class="col-sm-2 control-label">Code2</label>
								<div class="col-sm-10">
									<textarea class="form-control" name="code2"
											  placeholder="Enter Code" rows="20">{{ $code_blog->code2 }}</textarea>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-2">
									<p class="help-block">Add an Image.</p>
									<input type="file" name="imagePath">
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



