@extends('app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h1 class="text-center">Admin Page</h1>
					</div>


					<!-- Display Users-->
					<table class="table table-striped task-table">
					@if (count($users) > 0)
						<tbody>
							<tr>
								<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp; Name
								</th>
								<th>
									Email
								</th>
								<th>
									Role
								</th>
								<th>
									Update Password
								</th>

							</tr>
							@foreach ($users as $user)
							<tr>
								<td class="col-xs-2">
									@if($user->imagePath)
										<img src="../images/gravatars/{{ $user->imagePath }}" alt="gravatar"
											 height="40"  class="img-circle">
									@else
										<img src="../images/gravatars/pinkKitty.jpg" alt="gravatar"
											 height="40"  class="img-circle">
									@endif
									&nbsp;&nbsp;
									{{ $user->name }}
								</td>
								<td class="col-xs-2">
									{{ $user->email }}
								</td>
								<td class="col-xs-2">
									<select id="{{ $user->id }}" onchange="change_role(this.id, this.value)">
										<option value="user"
											@if ($user->role === "user")  selected @endif >User</option>
										<option value="admin"
											@if ($user->role === "admin")  selected @endif >Admin</option>
									</select>
									<div class="greenText" id="showChange{{$user->id }}" style="display: none" >
										Updated </div>
								</td>
								<td class="col-xs-2">
									<form  method="post" onsubmit="return false;">
										<input type="password" id="password{{$user->id }}" value="" placeholder="New Password"/>
										<input class="btn-warning" type="submit" onclick="change_password({{$user->id }})"
											   value="Change Password" />
										<div class="greenText" id="showUpdate{{$user->id }}" style="display: none" >
											Password Changed! </div>
									</form>
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

		function change_role($id, $role) {

			var userId = $id;
			var role = $role;
			//alert('this id is: ' + itemId);
			$.ajax({    //create an ajax request to load_page.php
				method: "POST",
				url: "change_role",
				data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
					userId: userId, role: role,
				}, //pass device id to show_comments
				dataType: "html",   //expect html to be returned
				success: function () {
					$( "#showChange" + userId).show("slow");
					//alert('changed role');
				},
				error: function(){
					alert('failure at change role');
				}
			});
		}

		function change_password($id) {
			var userId = $id;
			var password = $("input#password" + $id).val();
			//document.getElementById("password" + userId).value = '';
			//alert('this id is: ' + itemId);
			$.ajax({    //create an ajax request to load_page.php
				method: "POST",
				url: "change_password",
				data: {_token: "{{ csrf_token() }}",   // MUST PASS TOKEN! or get internal 500 error
					userId: userId, password: password,
				}, //pass device id to show_comments
				dataType: "html",   //expect html to be returned
				success: function () {
					$( "#showUpdate" + userId).show("slow");
					document.getElementById("password" + userId).value = '';//clear password text
					//alert('changed password');
				},
				error: function(){
					alert('failure at change Psswd');
				}
			});
		}
</script>
@endsection
