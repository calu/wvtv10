@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{trans('beheer.userchoice')}}
@stop

<?php
$users = User::getAllUsers();
?>
{{-- Content --}}
@section('content')
<div class='titeltekst'>{{ trans('beheer.title')}}</div>

<div class='col-md-8 col-md-offset-2'>
	{{ Form::open( array('action' => 'BeheersController@userchosen', 'class' => 'form-horizontal')) }}
	
	<div class="form-group {{ ($errors->has('id'))? 'has-error' : '' }}" for="id">
		{{ Form::label('chose_user', trans('beheer.choose'), array('class' => 'col-sm-2 control-label' )) }}
		<div class="col-sm-8">
			{{ Form::select('id', $users) }}
		</div>		
	</div>
	
	{{ Form::submit('Bevestig je keuze', array('class' => 'btn btn-primary')) }}
	
	{{ Form::close() }}
</div>
@stop