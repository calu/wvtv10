<?php

use Illuminate\Validation;

class Elegant extends Eloquent
{
/*    public $rules = array(
	);
*/
    protected $errors;
	
	protected $berichten = array(
		'required' => 'Het veld :attribute is noodzakelijk',
		'date' => 'Het veld :attribute moet een exacte datum zijn',
		'alpha' => 'Het veld :attribute mag enkel letters bevatten',
		'uniek' => 'Deze gebruiker werd reeds ingeschreven',
		'countrychosen' => 'Je moet een land kiezen',
		'userChosen' => 'Je moet een gebruiker kiezen',
		'user_chosen' => 'Kies een valide gebruiker',
	);

    public function validate($data)
    {	
        // make a new validator object
        $v = Validator::make($data, $this->rules, $this->berichten);

        // check for failure
        if ($v->fails())
        {
            // set errors and return false
            $this->errors = $v->errors();
            return false;
        }

        // validation pass
        return true;
    }

    public function errors()
    {
        return $this->errors;
    }
	
	
}
?>