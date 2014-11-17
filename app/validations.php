<?php
class CustomValidator extends Illuminate\Validation\Validator
{
	public function validateUserChosen($attribute, $value, $parameters)
	{
		return $value > 0;
	} 
		
}

Validator::resolver(function($translator, $data, $rules, $messages)
{
	return new CustomValidator($translator, $data, $rules, $messages);
});

?>