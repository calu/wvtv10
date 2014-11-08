<?php

class UserExtra extends Elegant {
	protected $fillable = array();
	
	/*
	 * foreign key in User
	 */
	public function user(){
		return $this->belongsTo('User');
	}	
}