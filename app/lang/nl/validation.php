<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| such as the size rules. Feel free to tweak each of these messages.
	|
	*/

	"accepted"         => "Het :attribute moet aanvaard worden.",
	"active_url"       => "Het :attribute is geen geldige URL.",
	"after"            => "Het :attribute moet een datum later dan :date zijn.",
	"alpha"            => "Het :attribute mag enkel letters bevatten.",
	"alpha_dash"       => "Het :attribute mag enkel letters, getallen en streepjes bevatten.",
	"alpha_num"        => "Het :attribute mag enkel letters en getallen bevatten.",
	"before"           => "Het :attribute moet een datum zijn voor :date.",
	"between"          => array(
		"numeric" => "Het :attribute moet gelegen zijn in interval :min - :max.",
		"file"    => "Het :attribute moet gelegen zijn in interval :min - :max kilobytes.",
		"string"  => "Het :attribute moet gelegen zijn in interval :min - :max karakters.",
	),
	"confirmed"        => "Het :attribute bevestiging komt niet overeen.",
	"date"             => "Het :attribute is geen valide datum.",
	"date_format"      => "Het :attribute komt niet overeen met het formaat :format.",
	"different"        => "Het :attribute en :other moeten verschillend zijn.",
	"digits"           => "Het :attribute moet bestaan uit :digits c.",
	"digits_between"   => "Het :attribute moet gelegen zijn in interval :min en :max digits.",
	"email"            => "Het :attribute formaat is niet geldig.",
	"exists"           => "Het geselecteerde :attribute is niet valide.",
	"image"            => "Het :attribute moet een figuur zijn.",
	"in"               => "Het geselecteerde :attribute is niet valide.",
	"integer"          => "Het :attribute moet een geheel getal zijn.",
	"ip"               => "Het :attribute moet een valide IP adres zijn.",
	"max"              => array(
		"numeric" => "Het :attribute mag niet groter zijn dan :max.",
		"file"    => "Het :attribute mag niet groter zijn dan :max kilobytes.",
		"string"  => "Het :attribute mag niet groter zijn dan :max karakters.",
	),
	"mimes"            => "Het :attribute moet een bestand van het type: :values.",
	"min"              => array(
		"numeric" => "Het :attribute moet tenminste zijn  :min.",
		"file"    => "Het :attribute moet tenminste zijn  :min kilobytes.",
		"string"  => "Het :attribute moet tenminste zijn  :min karakters.",
	),
	"not_in"           => "Het geselecteerde :attribute is niet valide.",
	"numeric"          => "Het :attribute moet een getal zijn.",
	"regex"            => "Het :attribute formaat is niet geldig.",
	"required"         => "Het :attribute veld is vereist.",
	"required_with"    => "Het :attribute veld is vereist als :values aanwezig zijn.",
	"required_without" => "Het :attribute veld is vereist als :values niet aanwezig zijn.",
	"same"             => "Het :attribute en :other moeten overeenstemmen.",
	"size"             => array(
		"numeric" => "Het :attribute moet zijn :size.",
		"file"    => "Het :attribute moet zijn :size kilobytes.",
		"string"  => "Het :attribute moet zijn :size karakters.",
	),
	"unique"           => "Het :attribute werd reeds gebruikt.",
	"url"              => "Het :attribute formaat is niet geldig.",
	
	"user_chosen"	=> "Je moet een naam kiezen",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => array(),

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => array(),

);
