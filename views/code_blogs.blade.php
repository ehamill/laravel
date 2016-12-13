@extends('app')

@section('content')

	<style type="text/css">
		h2 {
			text-align: center;
		}
		.description {white-space:pre-wrap;}
		fieldset {
			display: block;
			margin-left: 2px;
			margin-right: 2px;
			padding-top: 0.35em;
			padding-bottom: 0.625em;
			padding-left: 0.75em;
			padding-right: 0.75em;
			border: 2px groove;
		}
		legend {
			width: auto;
			margin: 0;
		}

	</style>

	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<span class="panel panel-default">
					<div class="panel-heading">
						<h1 class="text-center">{{ $title }}</h1>
					</div>
					<div class="panel-body">

						<!-- Display blogs-->
						@if (count($code_blogs) > 0)
							@foreach ($code_blogs as $code_blog)
								@if (Auth::user() && (Auth::user()->role == "admin"))
									<div class="col-xs-12">
										Priority: {{ $code_blog->priority }}
									</div>
								@endif

							<h2 id="{{ $pageName . $code_blog->id }}">  {{ $code_blog->title }}</h2>


							<div class="description col-xs-12">{!!  $code_blog->description !!} </div>

							@if ($code_blog	->code)
							<div class="code"><pre><code>{{ $code_blog->code }} </code></pre></div>
							@endif

							@if ($code_blog	->description2)
								<div class="description">{!! $code_blog->description2 !!} </div>
							@endif

							@if ($code_blog	->code2)
										<div class="code"><pre><code>{{ $code_blog->code2 }}</code></pre></div>
							@endif
							@if ($code_blog	->imagePath)
											<img src="{{ url('images/' .$code_blog->imagePath) }}" alt="pic" class="img-rounded">
											<!-- imagePath:  $adminBlog->imagePath }}{ print_r($adminBlog) }}<br/>-->
											<br/><br/>
							@endif

							@if (Auth::user() && (Auth::user()->role == "admin"))
							<!-- Edit button. Calls Route "Route::get('adminBlog/{id}', 'AdminBlogController..edit');"-->
							<!-- show edit and delete buttons if user =admin -->
							<form style="display:inline;" action="{{ url('Tutorial/edit/'. $code_blog->id) }}" method="GET">

								<button type="submit" class="btn btn-warning">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
									Edit
								</button>
							</form>

							<!-- Delete button -->
							<form style="display:inline;" action="{{ url('Tutorial/delete/' . $code_blog->id) }}" method="POST">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="_method" value="DELETE">

								<button type="submit" class="btn btn-danger">
									<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
									Delete
								</button>
							</form>
							@endif
						@endforeach
					@endif <!-- end if code_blog count != 0 -->
					</div>
					@if (Auth::user() && (Auth::user()->role == "admin"))
					<!-- New Item Form  -->
						<div class="text-center">
							<a  href="{{ url('Tutorial/create/' . $pageName ) }}" class="btn btn-success btn-lg active" role="button">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							Add New Post</a>
						</div>
					@endif
									<br/>
					<div class="panel-heading col-sm-12 text-center" >
						<!-- COMMENTS! COMMENTS! COMMENTS! COMMENTS! -->
						<!-- Show comments link -->
						<a href="#0" style="font-size: large" onclick="show_comments()" >
							<span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
							Comments({{ \App\CodeComment::get_count_code_blog_comments($pageName) }})</a>
					</div>
					<!-- Hidden Comments, loaded using ajax..see JS below -->
					<span id="hiddenComments" style="display: none;" >
						@if (count($comments) == 0)
							@if (Auth::guest())
								<h4 class="text-center">No Comments Yet! Login to add a comment or show some code.</h4>
							@endif
						@else
							@foreach ($comments as $comment)
								<fieldset class="col-xs-12">
									<legend>On {{date(("F j, Y"), strtotime($comment->created_at))}}, {{$comment->author}} wrote:</legend>

									<div class="col-xs-12" style="background-color: #e6e6e6">{{$comment->comment}}<br><br></div>

									@if ($comment->code)
										<div class="code"><pre><code>{{ $comment->code }} </code></pre></div>
									@endif
									<br><br>
									@if (Auth::user())
										@if (Auth::user()->role == "admin" || Auth::user()->id == $comment->userId )
											<form class="form-horizontal" action="{{ url('/deleteCodeBlogComment/' . $comment->id) }}" method="POST" >
												<input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" name="type" value="{{$pageName}}" />
												<button type="submit" class="btn btn-danger">
													<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
													Delete
												</button>
											</form>
										@endif
									@endif
								</fieldset>
							@endforeach
						@endif
					</span>

					<!-- If user logged in, show add a comment link -->
					<div  >
						@if (Auth::user())
							<div class="text-center">
								<button type="button" class="btn btn-info" data-toggle="modal"
										data-target="#myModal">Add a Comment</button>
							</div>
						@endif
					</div>

					<!-- Modal -->
					<div id="myModal" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header" style="background-color: #2e6da4; color: white">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title text-center">Add A Comment, Code is Optional</h4>
								</div>
								<div class="modal-body">
									<form class="form-horizontal" action="{{ url('/addCommentToCodeBlog') }}" method="POST" >
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="pageName" value="{{$pageName}}" />
										@if (Auth::user())
											<input type="hidden" name="userId" value="{{Auth::user()->id}}" />
										@endif
										<div class="form-group">
											<label for="addComment" class="col-sm-2 control-label">Add Comment</label>
											<div class="col-sm-10">
												<textarea name="comment" required class="newComment"  /></textarea>
											</div>
										</div>
										<div class="form-group">
											<label for="code" class="col-sm-2 control-label">Code</label>
											<div class="col-sm-10">
										<textarea class="form-control" name="code" placeholder="Enter Code" rows="10"></textarea>
											</div>
										</div>
										<div class="text-center">
											<button  class="btn btn-success">
											<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
											Submit</button>
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>

			</div>
		</div>
	</div>
</div>

	<script type="text/javascript">

		function show_comments() {
			$( "#hiddenComments").show();
		}

	</script>
@endsection
