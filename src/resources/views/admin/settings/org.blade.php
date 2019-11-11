@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Settings - Organization</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/settings">Settings</a>
			</li>
			<li class="active">
				Organization
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-lg-6 col-xs-12">
		<!-- Name & Logo -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Name & Logo
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('org_name','Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('org_name', Settings::getOrgName() ,array('id'=>'','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('org_tagline','Tagline/Title',array('id'=>'','class'=>'')) }}
						{{ Form::text('org_tagline', Settings::getOrgTagline() ,array('id'=>'','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						@if (trim(Settings::getOrgLogo()) != '')
							<img class="img img-responsive" src="{{ Settings::getOrgLogo() }}" />
						@endif
						{{ Form::label('org_logo','Logo',array('id'=>'','class'=>'')) }}
						{{ Form::file('org_logo',array('id'=>'','class'=>'form-control')) }}
					</div>
					 <div class="form-group">
						@if (trim(Settings::getOrgLogo()) != '')
							<img class="img img-responsive" src="{{ Settings::getOrgFavicon() }}" />
						@endif
						{{ Form::label('org_favicon','Favicon',array('id'=>'','class'=>'')) }}
						{{ Form::file('org_favicon',array('id'=>'','class'=>'form-control')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-xs-12">
		<!-- About -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> About
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<div class="form-group">
						{{ Form::label('about_main','Main',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_main', Settings::getAboutMain() ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('about_short','Short',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_short', Settings::getAboutShort() ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('about_our_aim','Our Aim',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_our_aim', Settings::getAboutOurAim() ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('about_who','Who' ,array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_who', Settings::getAboutWho() ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
 
@endsection