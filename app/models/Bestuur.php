<?php

class Bestuur extends \Eloquent {

	// Add your validation rules here
	public static $rules = array(
		// 'title' => 'required'
	);

	// Don't forget to fill this array
	protected $fillable = array();
	
	/*
	 * foreign key voor de tabel User
	 */
	public function user(){
		return $this->belongsTo('User');
	}
	

}