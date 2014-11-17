@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Edit Bestuur
@stop

{{-- Content --}}
@section('content')


<h4>{{trans('bestuur.actionedit')}}</h4>
@if ($errors->has())
	@foreach($errors->all() as $message)
		<div class='well'>
			{{ $message }}
		</div>
	@endforeach
@endif
<div class="well">
	{{ Form::open(array(
        'action' => array('BestuursController@update', $bestuur->id), 
        'method' => 'put',
        'class' => 'form-horizontal', 
        'role' => 'form'
        )) }}
        
        <div class="form-group" for="naam">
        	{{ Form::label('edit_naam', trans('bestuur.naam'), array('class' => 'col-sm-2 control-label')) }}
        	<div class="col-sm-10 noformfield">
        		<?php $naam = Bestuur::getFullname($bestuur->user_id); ?>
        		{{ $naam }}
        	</div>
        </div>
        
        <div class="form-group {{ ($errors->has('bestuursfunctie')) ? 'has-error' : '' }}" for="bestuursfunctie">

            {{ Form::label('edit_bestuursfunctie', trans('bestuur.bestuursfunctie'), array('class' => 'col-sm-2 control-label')) }}
            <div class="col-sm-10">
              {{ Form::text('bestuursfunctie', $bestuur->bestuursfunctie, array('class' => 'form-control', 'placeholder' => trans('bestuur.bestuursfunctie'), 'id' => 'edit_bestuursfunctie'))}}
            </div>
            {{ ($errors->has('bestuursfunctie') ? $errors->first('bestuursfunctie') : '') }}    			
    	</div>


 

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {{ Form::hidden('id', $bestuur->id) }}
                {{ Form::submit(trans('pages.actionedit'), array('class' => 'btn btn-primary'))}}
            </div>
      </div>
    {{ Form::close()}}
</div>


@stop