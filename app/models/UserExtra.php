<?php

class UserExtra extends Elegant {
	protected $fillable = array();
	
	// geboortedatum optioneel, maar moet datum zijn
	// straat, huisnr, box, city, zip, country mag leeg zijn
	// phone en gsm -- minstens 1 van beide moet ingevuld zijn
	public static $rules = array(
		'birthdate' => 'ifDate',
	);
	
	public static $messages = array(
		'birthdate.if_date' => 'De datum is niet valide',	
	);
	
	/*
	 * foreign key in User
	 */
	public function user(){
		return $this->belongsTo('User');
	}	
	
}