<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends \Cartalyst\Sentry\Users\Eloquent\User implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
	public function getRememberToken()
	{
	    return $this->remember_token;
	}
	
	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}
	
	public function getRememberTokenName()
	{
	    return 'remember_token';
	}	
	
	/*
	 * foreign key voor UserEXtra
	 */
	public function userExtra(){
		return $this->belongsTo('UserExtra');
	}
	
	/*
	 * getAllUsers
	 * 
	 * @purpose : In deze functie halen we alle gebruikers op, maar met een filter
	 *    De filter voegt de 3 eerste users toe als het hier de beheerder is, anders niet
	 * 
	 * @return : array van users
	 */
	public static function getAllUsers()
	{
		$isChief = (Sentry::check() && (Sentry::getUser()->hasAccess('admin')) );
		
		if ($isChief)
		{
			$users = User::all();
		} else 
		{
			$users = User::where('id', '>', 3)->get();	
		}
		
		$ret[-1] = "--kies het lid--";
		foreach($users AS $user)
		{
			$id = $user->id;
			$ret[$id] = $user->first_name." ".$user->last_name;
		}
		return $ret;
	}
	
}