@extends('app')

@section('content')

	<style type="text/css">
		.description {white-space:pre-wrap;}
	</style>

	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h1 class="text-center">{{ $title }}</h1>
					</div>

					@if (Auth::user())
						<div class="panel-body">
							<!-- New Item Form  -->
							<div class="text-center">
								<a  href="{{ url('/create/' . $itemType ) }}" class="btn btn-success btn-lg active" role="button">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Add New {{ucfirst($itemType)}}</a>
							</div>
						</div>
					@endif


					<!-- Display blogs-->
					<table class="table table-striped task-table">
					@if (count($items) > 0)
							<tbody>
							@foreach ($items as $item)
							<tr>
								<td class="col-xs-2">
									Author: {{ $item->author }}<br/><br/>
									@if($item->gravatar)
									<img src="images/gravatars/{{ $item->gravatar }}" alt="gravatar"
										 height="80" width="80" class="img-circle">
									@else
									<img src="images/gravatars/pinkKitty.jpg" alt="gravatar"
											 height="80" width="80" class="img-circle">
									@endif
									<br/>
								</td>

								<td class="col-xs-8">
									<span id="{{$itemType . $item->itemId }}" > Title: {{ $item->title }}</span><br/>
									<br/>
									Description: <span class="description">{{ $item->description }}</span><br/><br/>
									<!-- imagePath:  $adminBlog->imagePath }}{ print_r($adminBlog) }}<br/>-->
									@if ($item->imagePath)
										<img src="images/{{ $item->imagePath }}" alt="pic" class="img-rounded">
										<br/><br/>
									@endif
									@if ($item->documentPath)
										<a class="btn btn-primary" href="documents/{{ $item->documentPath }}"
											role="button">
											<span class="glyphicon glyphicon-file" aria-hidden="true"></span>
											{{ substr($item->documentPath,6) }}</a>
										<br/><br/>
									@endif

									<!-- Edit button. Calls Route "Route::get('adminBlog/{id}', 'AdminBlogController..edit');"-->
									<!-- show edit and delete buttons if user =admin -->
									@if (Auth::user() && ((Auth::user()->role == "admin") || (Auth::user()->id == $item->userId)))
									<form style="display:inline; float: right" action="{{ url('item/'. $item->itemId) }}" method="GET">
										<input type="hidden" name="role" value="{{ Auth::user()->role }}">

										<button type="submit" class="btn btn-warning">
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
											Edit
										</button>
									</form>

									<!-- Delete button -->
									<form style="display:inline; float: right" action="{{ url('deleteItem/' . $item->itemId) }}" method="POST">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="_method" value="DELETE">

										<button type="submit" class="btn btn-danger">
											<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
											Delete
										</button>
									</form>
									@endif

									<div >
										<!-- COMMENTS! COMMENTS! COMMENTS! COMMENTS! -->
										<!-- Show comments link -->
										<a href="#0" id="{{ $item->itemId }}" onclick="show_comments(this.id)" >
											<span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
											Comments({{ \App\Comment::get_count($item->itemId, $itemType) }})</a>
									</div>
									<!-- Hidden Comments, loaded using ajax..see JS below -->
									<span id="comment{{ $item->itemId }}" ></span>

									<!-- If user logged in, show add a comment link -->
									<div  >
										@if (Auth::user())
											<a href="#0" id="addCommentLink{{ $item->itemId }}"
											   role="button" onclick="show_add_comment_form({{ $item->itemId }})">
												<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
												Comment</a>
										@endif
									</div>
									<!-- hidden "add comment form"  -->
									<div class="col-sm-12 text-center blueText2" >
										<form onsubmit="add_comment({{ $item->itemId }})"
											  id="add_comment_form{{ $item->itemId }}" style="display: none">

											<input type="hidden" name="itemId" value="{{ $item->itemId }}" />
											<input type="hidden" name="type" value="{{$itemType}}" />

											Add Comment:
											<textarea id = "textBody{{ $item->itemId }}" class="newComment"  /></textarea>
											<br />
											<input  type="submit" value="Submit" />
											<br/><br/>

										</form>
									</div>

								</td>
							</tr>

							@endforeach
						</tbody>

					@endif

					</table>
					<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		function show_comments($id) {

			var itemId = $id;
			var genre = '{{$itemType}}';
			//var token = $token;

			// if user not logged in, set default values
			@if (Auth::guest())
			//not logged in, will only be able to read comment
			var userId = 0;
			var role = 'readOnly';
			@else
			//get userID and role, in show_comments.php these determine if person can add or delete comments
			var userId = '{{ Auth::user()->id}}';
			var role = '{{ Auth::user()->role}}';
			@endif
			$.ajax({    //create an ajax request to load_page.php
				method: "POST",
				url: "show_comments",
				data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
					itemId: itemId, genre: genre, userId: userId,
					role: role,
				}, //pass device id to show_comments
				dataType: "html",   //expect html to be returned
				success: function (data) {
					$("#comment" + itemId).html(data);
					//alert(data);
				},
				error: function(){
					alert('failure at Comments');
				}
			});
		}

		//on click, show add_comment form
		function show_add_comment_form($id) {
			$( "#add_comment_form" + $id).show("slow");

		}

		function add_comment($id) {

			var itemId = $id;
			var body = document.getElementById('textBody' + itemId).value; //id needs to be unique..thus use "+ itemId"
			var type = '{{$itemType}}';
				@if (!Auth::guest()){
				var userId = '{{ Auth::user()->id}}';
				var role = '{{ Auth::user()->role}}';
			}
			@endif
			//alert("body is: " + body + "itemId: " + itemId + type + "user" + userId);
			$.ajax({
				method: "POST",
				url: "add_comment",//post "add_comment" handled by AjaxController
				data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
					itemId: itemId, type: type, body: body, userId: userId,
				}, //pass device id to show_comments
				//dataType: "html",   //expect html to be returned
				success: function (data) {
					show_comments($id);
					$( "#add_comment_form" + $id).hide("slow");
				},
				error: function(){
					alert('failure at add Comments');
				}
			});

		}

		function delete_comment($id) {
			var id= $id;
			alert('deleteing itemId' + id);
			$.ajax({
				method: "POST",
				url: "delete_comment",//post "delete_comment" handled by AjaxController
				data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
					itemId: id
				}, //pass device id to show_comments
				dataType: "html",   //expect html to be returned
				success: function (data) {
					//alert('deleteed comment');
					//setTimeout(function(){ show_comments($id); }, 3000);
					$( "#hiddenComment" + $id).hide("slow");
					//$( "#add_comment_form" + $id).hide("slow");
				},
				error: function(){
					alert('failure at delete Comments');
				}
			});

		}
	</script>
@endsection
