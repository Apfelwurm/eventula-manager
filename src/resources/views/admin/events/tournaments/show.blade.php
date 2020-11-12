@extends ('layouts.admin-default')

@section ('page_title', $tournament->name . ' - Tournaments')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Tournaments - {{ $tournament->name }}</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/events/">Events</a>
			</li>
			<li class="breadcrumb-item">
				<a href="/admin/events/{{ $event->slug }}">{{ $event->display_name }}</a>
			</li>
			<li class="breadcrumb-item">
				<a href="/admin/events/{{ $event->slug }}/tournaments">Tournaments</a>
			</li>
			<li class="breadcrumb-item active">
				{{ $tournament->name }}
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._event.dashMini')

<!-- BRACKETS -->
@if (($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE') && $tournament->format != 'list')
	<div class="row">
		<div class="col-lg-12">
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-users fa-fw"></i> Brackets
				</div>
				<div class="card-body">
					@include ('layouts._partials._tournaments.brackets', ['admin' => true])
				</div>
			</div>
		</div>
	</div>
@endif

<div class="row">
	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-users fa-fw"></i> Participants
			</div>
			<div class="card-body">
				@if (($tournament->status == 'LIVE' && !$tournament->enable_live_editing )|| $tournament->status == 'COMPLETE' && $tournament->format != 'list')
					@include ('layouts._partials._tournaments.standings', ['admin' => true])
				@else
					@include ('layouts._partials._tournaments.participants', ['admin' => true, 'all' => true])
				@endif
			</div>
		</div>

	</div>
	<div class="col-lg-4">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Settings
			</div>
			<div class="card-body">
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug )) }}
					<div class="form-group">
						{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
						@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
							{{ Form::text('name', $tournament->name ,array('id'=>'name','class'=>'form-control', 'disabled'=>'true')) }}
						@else
							{{ Form::text('name', $tournament->name ,array('id'=>'name','class'=>'form-control')) }}
						@endif
					</div>
					<div class="row">
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('status','Status',array('id'=>'','class'=>'')) }}
							<!-- // TODO - Refactor -->
							@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
								{{
									Form::select(
										'status',
										array(
											'DRAFT'=>'DRAFT',
											'OPEN'=>'OPEN',
											'CLOSED'=>'CLOSED',
											'LIVE'=>'LIVE',
											'COMPLETE'=>'COMPLETE'
										),
										$tournament->status,
										array(
											'id'=>'status',
											'class'=>'form-control',
											'disabled'=>'true'
										)
									)
								}}
							@else
								{{
									Form::select(
										'status',
										array(
											'DRAFT'=>'DRAFT',
											'OPEN'=>'OPEN',
											'CLOSED'=>'CLOSED',
											'LIVE'=>'LIVE',
											'COMPLETE'=>'COMPLETE'
										),
										$tournament->status,
										array(
											'id'=>'status',
											'class'=>'form-control'
										)
									)
								}}
							@endif
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('format','Format',array('id'=>'','class'=>'')) }}
							{{ Form::text('format', ucfirst($tournament->format) .  ' - ' . $tournament->team_size ,array('id'=>'format','class'=>'form-control', 'disabled'=>'true')) }}
						</div>
					</div>
					<div class="form-group">
						{{ Form::label('game_id','Game',array('id'=>'','class'=>'')) }}
						@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
							{{
								Form::select(
									'game_id',
									Helpers::getGameSelectArray(),
									$tournament->game_id,
									array(
										'id'    	=> 'game_id',
										'class' 	=> 'form-control',
										'disabled'	=> 'true'
									)
								)
							}}
						@else
							{{
								Form::select(
									'game_id',
									Helpers::getGameSelectArray(),
									$tournament->game_id,
									array(
										'id'    => 'game_id',
										'class' => 'form-control'
									)
								)
							}}
						@endif
					</div>
					@if ($tournament->status != 'LIVE' && $tournament->status != 'COMPLETE')
						<div class="form-group">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', $tournament->description,array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
						</div>

						<div class="form-group">
							{{ Form::label('rules','Rules',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('rules', $tournament->rules,array('id'=>'rules','class'=>'form-control wysiwyg-editor')) }}
						</div>


					@endif
					@if ($tournament->status != 'LIVE' && $tournament->status != 'COMPLETE')
						<div class="form-group">
							<button type="submit" class="btn btn-secondary btn-block">Submit</button>
						</div>
					@endif
				{{ Form::close() }}
				<div class="form-group">
					@if (count($tournament->tournamentParticipants) >= 2 && $tournament->status != 'LIVE' && $tournament->status != 'COMPLETE')
						{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/start')) }}
							<button type="submit" name="action" value="start" class="btn btn-secondary btn-block">Start Tournament</button>
						{{ Form::close() }}
					@endif
					@if ($tournament->status == 'LIVE')
						{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/finalize')) }}
							<button type="submit" class="btn btn-secondary btn-block">Finalize Tournament</button>
						{{ Form::close() }}
						@if ($tournament->team_size != '1v1')
						<hr>
							@if ($tournament->enable_live_editing)
								{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/disableliveediting')) }}
								<button type="submit" class="btn btn-warning btn-block ">Disable Editing</button>
								{{ Form::close() }}
							@else
								{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/enableliveediting')) }}
								<button type="submit" class="btn btn-warning btn-block ">Enable Editing</button>
								{{ Form::close() }}
							@endif
						@endif
					@endif
				</div>
				@if ($tournament->status != 'COMPLETE')
					<hr>
					{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
						{{ Form::hidden('_method', 'DELETE') }}
						<button type="submit" class="btn btn-danger btn-block">Delete</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>

	</div>
</div>

@endsection