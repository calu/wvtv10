@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{trans('users.forgot')}}
@stop

{{-- Content --}}
@section('content')
<div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-6">
        {{ Form::open(array('action' => 'UserController@forgot', 'method' => 'post', 'class'=>'form-horizontal')) }}
            
            <h2 class='text-center'>{{trans('users.forgotupword')}}</h2>
            
            <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
            	<div class="col-xs-6 text-center">
                {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => trans('users.email'), 'autofocus')) }}
                {{ ($errors->has('email') ? $errors->first('email') : '') }}
                </div>
            </div>
            <div >
            	{{ Form::submit(trans('users.resendpword'), array('class' => 'btn btn-primary'))}}
            </div>
            

  		{{ Form::close() }}
  	</div>
</div>

@stop