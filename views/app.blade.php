<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Advanced Laravel Tutorial</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,400' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<!-- Highlights Code -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.8.0/styles/agate.min.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.8.0/highlight.min.js"></script>
	<script>hljs.initHighlightingOnLoad();</script>
	<!-- End Highlights Code -->
	<style type='text/css'>
		.hljs {
			white-space: pre;
			word-wrap: normal;}
		pre {-moz-tab-size: 3;} /* Code for Firefox */
		pre {-o-tab-size: 3;} /* Code for Opera 10.6-12.1 */
		pre {tab-size: 3;}



	</style>
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Laravel</a>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li>
						<form action="/search" method="POST">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input style="margin: 10px 0 0 15px" type="text"  name="search" id="search" placeholder="Search..."/>
							<button><span  class="glyphicon glyphicon-search"
										   data-toggle="tooltip" title="Search!!"  aria-hidden="false"></span>
							</button>
						</form>
					</li>
					<li role="presentation" class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="{{ url('/') }}" role="button"
						   aria-haspopup="true" aria-expanded="false">
							Laravel Tutorial <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="{{ url('Tutorial/LaraIntro') }}">Intro</a></li>
							<li><a href="{{ url('Tutorial/LaraUserAccounts') }}">Modifying the User Profile</a></li>
							<li><a href="{{ url('Tutorial/LaraBlog') }}">Creating a Blog</a></li>
							<li><a href="{{ url('Tutorial/LaraAjax') }}">Using AJAX</a></li>
							<li><a href="{{ url('Tutorial/LaraDropDown') }}">DropDown Hell</a></li>
							<li><a href="{{ url('Tutorial/LaraSearch') }}">Adding a Search</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('Tutorial/LaraErrors') }}">Errors</a></li>
							<li><a href="{{ url('/user_sessions') }}">User Sessions</a></li>

							<li><a href="{{ url('/errors') }}">Errors2</a></li>
							<li><a href="{{ url('/installing_laravel') }}">Installing Laravel</a></li>
						</ul>
					</li>
					<li role="presentation" class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="{{ url('/') }}" role="button"
						   aria-haspopup="true" aria-expanded="false">
							Asp.Net MVC Tutorial <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="{{ url('Tutorial/AspIntro') }}">Intro</a></li>
							<li><a href="{{ url('Tutorial/AspUserAccounts') }}">Modifying the User Profile</a></li>
							<li><a href="{{ url('/') }}">Asp Tut</a></li>
							<li><a href="{{ url('/') }}">Asp Tut</a></li>
							<li><a href="{{ url('/') }}">Asp Tut</a></li>
							<li><a href="{{ url('/') }}">Asp Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
						</ul>
					</li>
					<li role="presentation" class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="{{ url('/') }}" role="button"
						   aria-haspopup="true" aria-expanded="false">
							Node Tutorial <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="{{ url('Tutorial/NodeIntro') }}">Intro</a></li>
							<li><a href="{{ url('Tutorial/NodeUserAccounts') }}">Modifying the User Profile</a></li>
							<li><a href="{{ url('/') }}">Asp Tut</a></li>
							<li><a href="{{ url('/') }}">Asp Tut</a></li>
							<li><a href="{{ url('/') }}">Asp Tut</a></li>
							<li><a href="{{ url('/') }}">Asp Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
							<li><a href="{{ url('/') }}">Laravel Tut</a></li>
						</ul>
					</li>

					<li><a href="{{ url('/adminBlog') }}">NEWS!</a></li>
					<li><a href="{{ url('/devices') }}">Devices</a></li>
					<li><a href="{{ url('/phones') }}">Phones</a></li>
					<li><a href="{{ url('/switches') }}">Switches</a></li>
					<li><a href="{{ url('/sites') }}">Sites</a></li>

				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								@if (Auth::user()->imagePath  == '')
									<img src="/images/gravatars/pinkKitty.jpg" alt="gravatar"
										 width="40px" height="40px" class="img-circle">
								@else
									<img src="/images/gravatars/{{ Auth::user()->imagePath }}" alt="gravatar"
										 width="40px" height="40px" class="img-circle">
								@endif
								{{ Auth::user()->name}}
								<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="showProfile/">Profile</a></li>
								<li><a href="{{ url('/auth/logout') }} ">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')


</body>
<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

</html>
