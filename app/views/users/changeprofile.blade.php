@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{trans('pages.changeprofile')}}
@stop
<?php
$isAdmin = Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary'));
$extra = DB::table('user_extras')->where('user_id', $id)->first();

if ($extra->birthdate == "0000-00-00") $extra->birthdate = null;
$selectTitle = AppHelper::enum_to_array('user_extras', 'title');
$selectCountry = DB::table('countries')->lists('name');
array_unshift($selectCountry,"--- kies een land ---");

$urlterug = url('inhoud');

?>
{{-- Content --}}
@section('content')
<div class='row'>
	{{-- knop terugkeren --}}
	<div><a href='{{ $urlterug }}' class='groen'>keer terug</a></div>
	
	{{-- formulier --}}
	<div class='col-md-12 roodkader'>
		<h2 class='titeltekst'>{{ trans('pages.changeprofile') }} {{ $id }}</h2>
		@if ($errors->has())
			@foreach($errors->all() as $message)
				<div class='well rood'>
					{{ $message }}
				</div>
			@endforeach
		@endif
		
		{{ Form::open( array( 'method' => 'post', 'route' => 'storeprofile', 'class' => 'form-inline' )) }}
			
			{{ Form::hidden('id', $id ) }}
			
			<div class='grijstitel'>Naam en adres</div>
			<div class='row'>
				<div class='form-group fullwidth'>
					{{ Form::label('edit_title', trans('pages.persontitle'), array('class' => 'label-1 control-label' )) }}
					{{ Form::select('title', $selectTitle, $extra->title, array('class' => 'mycol-120', 'id' => 'edit_title' )) }}
					
					{{ Form::label('edit_first_name', trans('pages.first_name'), array('class' => 'label-60 control-label' )) }}
					{{ Form::text('first_name', $user->first_name, array('class' => 'mycol-200', 'placeholder' => trans('pages.first_name'), 'id' => 'edit_first_name')) }}
					{{ ($errors->has('first_name') ? $errors->first('first_name') : '' ) }}
					
					{{ Form::label('edit_last_name', trans('pages.last_name'), array('class' => 'label-80 control-label' )) }}
					{{ Form::text('last_name', $user->last_name, array('class' => 'mycol-200', 'placeholder' => trans('pages.last_name'), 'id' => 'edit_last_name')) }}
					{{ ($errors->has('last_name') ? $errors->first('last_name') : '' ) }}					
				</div>
			</div>
			<div class='clearfix'>&nbsp;</div>
			<div class='row'>
				<div class='form-group fullwidth'>
					{{ Form::label('edit-street', trans('pages.street'), array('class' => 'label-1 control-label' )) }}
					{{ Form::text('street', $extra->street, array('class' => 'mycol-400', 'placeholder' => trans('pages.street'), 'id' => 'edit_street' )) }}
					{{ ($errors->has('street') ? $errors->first('street') :  '' ) }}
					
					{{ Form::label('edit-housenr', trans('pages.housenr'), array('class' => 'label-60 control-label' )) }}
					{{ Form::text('housenr', $extra->housenr, array('class' => 'mycol-70', 'placeholder' => trans('pages.housenr'), 'id' => 'edit_housenr' )) }}
					{{ ($errors->has('housenr') ? $errors->first('housenr') :  '' ) }}					
				</div>
			</div>
			<div class='clearfix'>&nbsp;</div>
			<div class='row'>
				{{-- zip city country --}}
				<div class='form-group fullwidth'>
					{{ Form::label('edit-zip', trans('pages.zip'), array('class' => 'label-60 control-label' )) }}
					{{ Form::text('zip', $extra->zip, array('class' => 'mycol-70', 'placeholder' => trans('pages.zip'), 'id' => 'edit_zip' )) }}
					{{ ($errors->has('zip') ? $errors->first('zip') :  '' ) }}	
					
					{{ Form::label('edit-city', trans('pages.city'), array('class' => 'label-60 control-label' )) }}
					{{ Form::text('city', $extra->city, array('class' => 'mycol-200', 'placeholder' => trans('pages.city'), 'id' => 'edit_city' )) }}
					{{ ($errors->has('city') ? $errors->first('city') :  '' ) }}
					
					{{ Form::label('edit-country', trans('pages.country'), array('class' => 'label-60 control-label' )) }}
					{{ Form::select('country', $selectCountry, $extra->country, array('class' => 'mycol-200', 'placeholder' => trans('pages.country'), 'id' => 'edit_country' )) }}
					{{ ($errors->has('country') ? $errors->first('country') :  '' ) }}					
														
				</div>
			</div>		
			
			<div class="grijstitel">persoonlijke data</div>
			<div class='row'>
				{{-- e-mail en geboortedatum --}}
					{{ Form::label('edit-email', trans('pages.email'), array('class' => 'label-60 control-label' )) }}
					{{ Form::text('email', $user->email, array('class' => 'mycol-200', 'placeholder' => trans('pages.email'), 'id' => 'edit_email' )) }}
					
					{{ ($errors->has('email') ? $errors->first('email') :  '' ) }}		
					
					{{ Form::label('edit-birthdate', trans('pages.birthdate'), array('class' => 'label-100 control-label' )) }}
					{{ Form::text('birthdate', $extra->birthdate, array('class' => 'mycol-200 datepicker', 'placeholder' => trans('pages.birthdate'), 'data-datepicker' => 'datepicker', 'id' => 'edit_birthdate' )) }}
													
			</div>
			@if ($errors->has('birthdate'))
				<div class='rood'>{{ $errors->first('birthdate') }}</div>
			@endif
			
			<div class='clearfix'>&nbsp;</div>
			<div class='row'>
				{{-- telefoon en gsm --}}
					{{ Form::label('edit-phone', trans('pages.phone'), array('class' => 'label-60 control-label' )) }}
					{{ Form::text('phone', $extra->phone, array('class' => 'mycol-200', 'placeholder' => trans('pages.phone'), 'id' => 'edit_phone' )) }}
					{{ ($errors->has('phone') ? $errors->first('phone') :  '' ) }}	
					
					{{ Form::label('edit-gsm', trans('pages.gsm'), array('class' => 'label-1 control-label' )) }}
					{{ Form::text('gsm', $extra->gsm, array('class' => 'mycol-200', 'placeholder' => trans('pages.gsm'), 'id' => 'edit_gsm' )) }}
					{{ ($errors->has('gsm') ? $errors->first('gsm') :  '' ) }}										
			</div>	
			<div class='clearfix'>&nbsp;</div>
			<div class='row' style='color:green; width:100%; text-align:center;'>De wachtwoorden worden elders gewijzigd (zie home)</div>
			<div class='grijstitel'>professionele data</div>
			<div class='row'>
				{{-- diplome en functie --}}
					{{ Form::label('edit-diploma', trans('pages.diploma'), array('class' => 'label-60 control-label' )) }}
					{{ Form::text('diploma', $extra->diploma, array('class' => 'mycol-200', 'placeholder' => trans('pages.diploma'), 'id' => 'edit_diploma' )) }}
					{{ ($errors->has('diploma') ? $errors->first('diploma') :  '' ) }}	
					
					{{ Form::label('edit-position', trans('pages.position'), array('class' => 'label-60 control-label' )) }}
					{{ Form::text('position', $extra->position, array('class' => 'mycol-200', 'placeholder' => trans('pages.position'), 'id' => 'edit_position' )) }}
					{{ ($errors->has('position') ? $errors->first('position') :  '' ) }}									
			</div>
			<div class='clearfix'>&nbsp;</div>
			<div class='row'>
				{{-- werkplaats --}}
					{{ Form::label('edit-workplace', trans('pages.workplace'), array('class' => 'label-80 control-label' )) }}
					{{ Form::text('workplace', $extra->workplace, array('class' => 'mycol-600', 'placeholder' => trans('pages.workplace'), 'id' => 'edit_workplace' )) }}
					{{ ($errors->has('workplace') ? $errors->first('workplace') :  '' ) }}				
			</div>
			
			@if($isAdmin)
				<?php
				// Haal alle mogelijke groepen
				$specsymbol = array('{','}');
				$activeGroup = array();
				foreach($userGroups AS $u)
				{
					$temp = str_replace($specsymbol, '', $u);
					$activeGroup[] = $temp;
				}
				
				// Als user activated is ... zet dan checked
				if ($user->activated) $checked = 'checked'; else $checked = '';
				if ($user->last_login) $last_login = $user->last_login; else $last_login = "nog nooit aangemeld";
				?>
				
				<div class='grijstitel'>enkel voor beheerder</div>
				<div class='row'>
					{{-- status --}}
					{{ Form::label('edit-groups', trans('pages.groups'), array('class' => 'label-60 control-label')) }}

					@foreach ($allGroups AS $group)
					<input type='checkbox' {{ (in_array($group->name,$activeGroup) ? 'checked' : null )}} name='{{ $group->name }}' value='{{ $group->name }}'>{{ $group->name }} 
					@endforeach
				</div>
				<div class='clearfix'>&nbsp;</div>
				<div class='row'>
					{{ Form::label('edit-lastloggedin', trans('pages.lastloggedin'), array('class' => 'label-120 control-label')) }}
					{{ Form::text('lastloggedin', $last_login, array('class'=>'mycol-200', 'placeholder' => '', 'disabled' => 'disabled', 'id' => 'lastloggedin')) }}
				</div>
				<div class='clearfix'>&nbsp;</div>
				<div class='row'>
					{{-- in het bestuur? --}}
					<?php if ( Bestuur::isMemberOfBestuur($id)) $checked = 'ja'; else $checked = 'neen'; ?>
					{{ Form::label('edit_inbeheer', trans('pages.inbeheer'), array('class' => 'label-120 control-label' )) }}
					{{ Form::text('inbeheer', $checked, array('class' => 'mycol-100', 'placeholder' => '', 'disabled' => 'disabled', 'id' => 'inbeheer')) }}
				</div>
			@endif
			<div class='clearfix'>&nbsp;</div>
			{{ Form::submit('Spaar het profiel', array('class' => 'btn btn-primary')) }}
		{{ Form::close() }}
	</div>

	{{-- knop terugkeren --}}
	<div><a href='{{ $urlterug }}' class='groen'>keer terug</a></div>		
</div>

@stop