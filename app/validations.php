<?php
class CustomValidator extends Illuminate\Validation\Validator
{
	public function validateUserChosen($attribute, $value, $parameters)
	{
		return $value > 0;
	} 
	
	public function validateIfDate($attribute, $value, $parameters)
	{
		if ($value == null) return true;
		
		// Geboortedatum moet juiste formaat hebben
		$check =  preg_match("/^[0-3][0-9]-[0-1][0-9]-[0-9]{4}$/",$value);
		if (!$check) return false;
		// Geboortedatum moet minstens van vorig jaar zijn 
		$date = DateTime::createFromFormat("d-m-Y", $value);
        $jaar = $date->format("Y");
		$currentyear = date("Y");
		return ($jaar < $currentyear);
	}
	
	public function validatePhoneRule($attribute, $value, $parameters)
	{
		return preg_match("/^([0-9\s\-\+\(\)\.\/]*)$/", $value); 
	}

	
}

Validator::resolver(function($translator, $data, $rules, $messages)
{
	return new CustomValidator($translator, $data, $rules, $messages);
});

?>