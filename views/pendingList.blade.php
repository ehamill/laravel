@extends('app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h1 class="text-center">Pending Posts</h1>
					</div>

					<!-- Display blogs-->
					<table class="table table-striped task-table">
					@if (count($items) > 0)
						<tbody>
							@foreach ($items as $item)

							<!--   SHOW ITEM DETAILS -->
							<!-- Hidden Text "Approved" and "Deleted" -->

							<tr id="hideRow{{$item->itemId}}">
								<td class="col-xs-2">
									Author: {{ $item->author }}<br/><br/>
									@if($item->gravatar)
									<img src="../images/gravatars/{{ $item->gravatar }}" alt="gravatar"
										 height="80" width="80" class="img-circle">
									@else
									<img src="images/gravatars/pinkKitty.jpg" alt="gravatar"
											 height="80" width="80" class="img-circle">
									@endif
									<br/>
								</td>

								<td class="col-xs-8">
									Title: {{ $item->title }}<br/>
									<br/>
									Description: {{ $item->description }}<br/><br/>
									<!-- imagePath:  $adminBlog->imagePath }}{ print_r($adminBlog) }}<br/>-->
									@if ($item->imagePath)
										<img src="../images/{{ $item->imagePath }}" alt="pic" class="img-rounded">
										<br/><br/>
									@endif
									@if ($item->documentPath)
										<a class="btn btn-primary" href="documents/{{ $item->documentPath }}"
										   	role="button">
											<span class="glyphicon glyphicon-file" aria-hidden="true"></span>
											{{ substr($item->documentPath,6) }}</a>
										<br/><br/>
									@endif

									<!-- Approve button -->
									<button id="{{ $item->itemId }}" onclick="approve_post(this.id)"
											class="btn btn-success">
											<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
											Approve
									</button>

									<!-- Delete button -->
									<button onclick="delete_post({{ $item->itemId }})" type="submit" class="btn btn-danger">
										<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
										Delete
									</button>
								</td>
							</tr>

							@endforeach
						</tbody>

					@endif

					</table>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		function approve_post($id) {

			var itemId = $id;
			//alert('this id is: ' + itemId);
            $.ajax({    //create an ajax request to load_page.php
				method: "POST",
				url: "approve_post",
				data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
					itemId: itemId,
				}, //pass device id to show_comments
				dataType: "html",   //expect html to be returned
				success: function () {
					$( "#showApproved" + itemId).show("slow");
					$( "#hideRow" + itemId).hide("slow");
					//alert('approved post');
				},
				error: function(){
					alert('failure at approve post');
				}
			});
		}

		function delete_post($id) {

			var itemId = $id;
			//alert('this id is: ' + itemId);
			$.ajax({    //create an ajax request to load_page.php
				method: "POST",
				url: "delete_post",
				data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
					itemId: itemId,
				}, //pass device id to show_comments
				dataType: "html",   //expect html to be returned
				success: function () {
					$( "#showDeleted" + itemId).show("slow");
					$( "#hideRow" + itemId).hide("slow");
					//alert('deleted post');
				},
				error: function(){
					alert('failure at delete post');
				}
			});
		}
	</script>
@endsection
