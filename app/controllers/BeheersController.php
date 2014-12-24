<?php

class BeheersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /beheers
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /beheers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /beheers
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /beheers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /beheers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /beheers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /beheers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	
	
	/*****
	 * Toegevoegde functies
	 * 
	 * functie 1 : checkmail - testen van het versturen van een e-mail
	 */
	public function checkmail()
	{
		$data = array( 'firstname' => 'de tester');
		$afzender = 'johan.calu@gmail.com';
		Mail::send('beheers.emails.checkmail', $data, function($message)
		{
			$message->to('johan.calu@gmail.com', 'Johan Calu Tester')->subject('Een testbericht voor Laravel/Vagrant');
		});
		Session::flash('success', "Een mail werd verstuurd naar {$afzender} als test");
		return Redirect::route('home');
	}
	
	/*
	 * functie 2 : init - het initialiseren van de databank
	 * 
	 */
	public function init()
	{
		// We beginnen met de UserExtra voor de 3 bestaande users
		//    id = 1  johan.calu@gmail.com
	 	$date = new DateTime;
		$extraID = DB::table('user_extras')->insertGetID(
			array(
			  'user_id' => 1,
			  'birthdate' => "1951-09-06",
			  'street' => "rozenlaan",
			  'housenr' => "26",
			  'box' => '',
			  'city' => "Oostende",
			  'zip' => "8400",
			  'country' => "Belgium",
			  'phone' => "059 123456",
			  'gsm' => "0476 654321",
			  'workplace' => "thuis",
			  'position' => "the boss",
			  'title' => "dr",
			  'diploma' => "dr.",
			  'created_at' => $date,
			  'updated_at' => $date,
			)
		);
		
		//    id = 2 johan.calu@telenet.be
		$extraID = DB::table('user_extras')->insertGetID(
			array(
			  'user_id' => 2,
			  'birthdate' => "1951-09-06",
			  'street' => "rozenlaan",
			  'housenr' => "26",
			  'box' => '',
			  'city' => "Kortrijk",
			  'zip' => "8500",
			  'country' => "Belgium",
			  'phone' => "059 123456",
			  'gsm' => "0476 654321",
			  'workplace' => "thuis",
			  'position' => "the boss",
			  'title' => "dr",
			  'diploma' => "dr.",
			  'created_at' => $date,
			  'updated_at' => $date,
			)
		);
		
		//    id = 3 johan@johancalu.be
		$extraID = DB::table('user_extras')->insertGetID(
			array(
			  'user_id' => 3,
			  'birthdate' => "1951-09-06",
			  'street' => "rozenlaan",
			  'housenr' => "26",
			  'box' => '',
			  'city' => "Brugge",
			  'zip' => "8000",
			  'country' => "Belgium",
			  'phone' => "059 123456",
			  'gsm' => "0476 654321",
			  'workplace' => "thuis",
			  'position' => "the boss",
			  'title' => "dr",
			  'diploma' => "dr.",
			  'created_at' => $date,
			  'updated_at' => $date,
			)
		);
	
	    // haal de oude personen op uit wvtv_persons en bestuur uit wvtv_bestuur
	    $persons = DB::select("SELECT * FROM wvtv_person");
		$bestuur = DB::select("SELECT * FROM wvtv_bestuur");	
		
		$index = 1;
		foreach( $persons AS $person)
		{
			// De user met id = 1 is in de vroegere databank de administrator en moet hier niet opnieuw ingevuld worden
			if ($person->id != 1)
			{
				// ga na of deze persoon reeds bestaat - het e-mail adres is uniek, dus daar testen we op
				$personEmail = $person->email;
				$aantal = DB::table('users')->where('email','=',$personEmail)->count();
				// als er reeds users met dit e-mail adres in onze nieuwe databank zit
				if ($aantal > 0)
				{
					print("<br />#### deze gebruiker ({$personEmail}) bestaat reeds!");
				} else {
					// deze user toevoegen aan User
					$thisUser = Sentry::getUserProvider()->create( array(
						'email' => $person->email,
						'password' => "wvtvnieuw",
						'first_name' => $person->firstname,
						'last_name' => $person->lastname,
						'activated' => 1,
					));
					
					// vervolgens de gegevens voor deze persoon toevoegen in user_extras
					$date = new DateTime;
					$extraID = DB::table('user_extras')->insertGetID(
					  array(
					  	'user_id' => $thisUser->id,
						'birthdate' => $person->birthdate,
						'street' => $person->address,
						'housenr' => $person->housenr,
						'box' => '',
						'city' => $person->city,
						'zip' => $person->zip,
						'country' => $person->country,
						'phone' => $person->phone,
						'gsm' => $person->gsm,
						'workplace' => $person->werkplaats,
						'position' => $person->functie,
						'title' => $person->title,
						'diploma' => $person->diploma,
						'created_at' => $date,
						'updated_at' => $date,					  	
					  )
					 );
					
					 // Nu kijken we of dit een bestuurder is
					 $member = self::isBestuurder($person->id, $bestuur);
					 if ($member != null)
					 {
					 	// toevoegen aan Bestuur
					 	$bestuurID = DB::table('bestuurs')->insertGetID(
					 		array(
					 			'user_id' => $thisUser->id,
					 			'bestuursfunctie' => $member->bestuursfunctie,
					 			'sortnr' => $index++,
					 			'created_at' => $date,
					 			'updated_at' => $date,
							)
						);
					 }
					 print("<br /> user {$person->email} werd uitgeschreven");
				}
				
			}
		}

        print("<br />Nu voegen we ook de diverse documenten toe");
        $documenten = DB::select('SELECT * FROM wvtv_documenten');
		foreach($documenten AS $document)
		{
			$docok = DB::table('documents')->insertGetID(
				array(
					'title' => $document->title,
					'description' => $document->description,
					'url' => $document->url,
					'date' => $document->date,
					'sortnr' => $document->sortnr,
					'localfilename' => $document->localfilename,
					'author' => $document->author,
					'alwaysvisible' => $document->alwaysvisible,
					'type' => 'document'
				)
			);
			
		}		
	    print('<br />Alle documenten overgeplaatst');
	 
		$links = DB::select('SELECT * FROM wvtv_links');
		foreach($links AS $link)
		{
			$linkok = DB::table('documents')->insertGetID(
				array(
					'title' => $link->title,
					'description' => $link->description,
					'url' => $link->url,
					'sortnr' => $link->sortnr,
					'localfilename' => $link->localfilename,
					'author' => $link->author,
					'alwaysvisible' => $link->alwaysvisible,
					'type' => 'links'
				)
			);
		}
	    print('<br />Alle links overgeplaatst.');
	 	
	 	$navormingen = DB::select('SELECT * FROM wvtv_navorming');
		foreach($navormingen AS $navorming)
		{
			$linkok = DB::table('documents')->insertGetID(
				array(
					'title' => $navorming->title,
					'description' => $navorming->description,
					'url' => $navorming->url,
					'sortnr' => $navorming->sortnr,
					'localfilename' => $navorming->localfilename,
					'author' => $navorming->author,
					'alwaysvisible' => $navorming->alwaysvisible,
					'type' => 'navorming'
				)
			);
		}
        print('<br />Alle navormingen overgeplaatst');
		
		
		$transfusies = DB::select('SELECT * FROM wvtv_transfusie');
		foreach($transfusies AS $transfusie)
		{
			$transfusieok = DB::table('documents')->insertGetID(
				array(
					'title' => $transfusie->title,
					'description' => $transfusie->description,
					'url' => $transfusie->url,
					'sortnr' => $transfusie->sortnr,
					'localfilename' => $transfusie->localfilename,
					'author' => $transfusie->author,
					'alwaysvisible' => $transfusie->alwaysvisible,
					'type' => 'transfusie'
				)
			);			
		}
		print("<br />Alle transfusies overgeplaatst");
	

		$wetgeving = DB::select('SELECT * FROM wvtv_wetgeving');
		foreach($wetgeving AS $wet)
		{
			$wetgevingok = DB::table('documents')->insertGetID(
				array(
					'title' => $wet->title,
					'description' => $wet->description,
					'url' => $wet->url,
					'sortnr' => $wet->sortnr,
					'localfilename' => $wet->localfilename,
					'author' => null,
					'alwaysvisible' => $wet->alwaysvisible,
					'type' => 'wetgeving'
				)
			);			
		}
		print("<br />Alle wetgeving overgeplaatst");
	}

     function isBestuurder($id, $bestuur)
	 {
	 	foreach($bestuur AS $member)
		{
			if ($member->user_id == $id)
				return $member;
		}		
	 	return null;
	 }
	 
	 /*
	  * editprofile
	  */
	 public function editprofile()
	 {
	 	return View::make('beheers.userchoice');
	 }

     /*
	  * userchosen
	  * 
	  * hier kom je vanuit het formulier dat je met editprofile hebt opgeroepen
	  */
	 public function userchosen()
	 {
	 	$item = Input::get('id');
	 	return Redirect::route('changeprofile', array('id' => $item));
	 }
	 
	 /*
	  * restoredb
	  * 
	  * @purpose : trying to restore the database - as far as we can
	  *    - this is mainly the documents table and there the sortnrs
	  * @args : none
	  * @return : status
	  * 
	  */
	  public function restoredb()
	  {
	  	// We beginnen met de tabel documenten
	  	//    haal de onderscheiden types op
	  	$types = AppHelper::enum_to_array('documents', 'type');
		foreach($types AS $type)
		{
			// haal nu alle rijen voor dit type, geordend volgens sortnr
			$tabel = db::table('documents')->where('type',$type)->orderBy('sortnr')->get();
			// doorloop deze tabel en hernummer het sortnr
			$sortnr = 1;
			print("<br />++++++++ TYPE = {$type}");
			foreach($tabel AS $rij)
			{
				//$rij->sortnr = $sortnr++;
				// hier nog sparen !!!!
				db::table('documents')->where('id', $rij->id)->update(array('sortnr' => $sortnr++));
			}
			
		}
		die("RESTORED");
	  }

}