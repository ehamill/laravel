@extends('app')

@section('content')
	<style type="text/css">
		.description {white-space:pre-wrap;}
		mark {
			background-color: #fff733;
		}
	</style>

	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h1 class="text-center">{{ $html }}</h1>
					</div>

					<!-- Display Search Results-->
					@if (count($code_blogs) > 0)
						@foreach ($code_blogs as $code_blog)
							<h2>  {!! $code_blog->title !!}</h2>

							<div class="description col-xs-12">{!!  $code_blog->description !!} </div>

							@if ($code_blog	->code)
								<div class="code"><pre><code>{{ $code_blog->code }} </code></pre></div>
							@endif

							@if ($code_blog	->description2)
								<div class="description">{{ $code_blog->description2 }}</div>
							@endif

							@if ($code_blog	->code2)
								<div class="code"><pre><code>{{ $code_blog->code2 }}</code></pre></div>
							@endif
							@if ($code_blog	->imagePath)
								<img src="{{ url('images/' .$code_blog->imagePath) }}" alt="pic" class="img-rounded">
								<!-- imagePath:  $adminBlog->imagePath }}{ print_r($adminBlog) }}<br/>-->
								<br/><br/>
							@endif
							<hr>
						@endforeach
					@endif


					<table class="table table-striped task-table">
					@if (count($items) > 0)
						<tbody>
							@foreach ($items as $item)
							<tr>

								<td class="col-xs-8">
									Title: {!! $item->title !!} <br/>
									<br/>
									Description: {!! $item->description !!}  <br/><br/>
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

	</script>
@endsection
