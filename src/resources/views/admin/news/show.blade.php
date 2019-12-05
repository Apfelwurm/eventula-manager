@extends ('layouts.admin-default')

@section ('page_title', 'News')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">News</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/news/">News</a>
			</li>
			<li class="active">
				{{ $newsArticle->title }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil fa-fw"></i> Edit {{ $newsArticle->title }}
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/news/' . $newsArticle->slug, 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('title','Title',array('id'=>'','class'=>'')) }}
						{{ Form::text('title', $newsArticle->title ,array('id'=>'title','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('article','Article',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('article', $newsArticle->article, array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('tags','Tags',array('id'=>'','class'=>'')) }}<small> - Separate with a comma</small>
						{{ Form::text('tags', $newsArticle->getTags(), array('id'=>'', 'class'=>'form-control')) }}
					</div>
					<button type="submit" class="btn btn-success btn-block">Submit</button> 
				{{ Form::close() }}
				<hr>
				{{ Form::open(array('url'=>'/admin/news/' . $newsArticle->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					<button type="submit" class="btn btn-danger btn-block">Delete</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Stats
			</div>
			<div class="panel-body">
				<!-- // TODO -->
				To do
			</div>  
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-comments fa-fw"></i> Comments
			</div>
			<div class="panel-body">
				@foreach ($comments as $comment)
					@include ('layouts._partials._news.comment-warnings')
					@include ('layouts._partials._news.comment')
				@endforeach
				{{ $comments->links() }}
			</div>  
		</div>
	</div>
</div>
 
@endsection