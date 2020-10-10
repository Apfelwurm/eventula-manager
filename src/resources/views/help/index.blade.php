@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('help.help'))

@section ('content')

<div class="container">

	<div class="page-header">
		<h1>@lang('help.help')</h1> 
	</div>
	<div class="row">
		@foreach ($helpCategorys as $helpCategory)
			<div class="well well-sm col-sm-3 col-xs-6">
				<h4><a href="/help/{{ $helpCategory->slug }}">{{ $helpCategory->name }}</a></h4>
				@if ($helpCategory->event)
					<h5>@lang('help.event') {{ $helpCategory->event->display_name }}</h5>
				@endif
				<p>{{ $helpCategory->description }}</p>							
			</div>
		@endforeach
	</div>

</div>

@endsection
