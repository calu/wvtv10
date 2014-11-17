<?php

class Bestuur extends \Eloquent {

	// Add your validation rules here
	public static $rules = array(
		'id' => 'required',
	);

	// Don't forget to fill this array
	protected $fillable = array('bestuursfunctie');
	
	/*
	 * foreign key voor de tabel User
	 */
	public function user(){
		return $this->belongsTo('User');
	}
	
	/*
	 * getShortList
	 * 
	 * returns : de eerste 3 items in de lijst (georden volgens sortnr). 
	 *    Van elk item wordt enkel een string teruggestuurd met daarin "voornaam familienaam ( bestuursfunctie )";
	 * 
	 */
	public static function getShortList()
	{
		// haal de 3 eerste items (geordend volgens sortnr)
		$bestuur = Bestuur::all()->sortby('sortnr')->toArray();
		$size = sizeof($bestuur);
		$max = ($size > 4)?4:$size;

		for ($i=0; $i < $max; $i++)
		{
			$item = $bestuur[$i];
			// met de user_id zoeken we de entry in de users tabel
			$user_array = DB::table('users')->where('id', $item['user_id'])->get();
			// Als de user bestaat ! maak dan de voornaam - familienaam string
			if (sizeof($user_array) == 1)
			{
				$user = $user_array[0];
				$line = $user->first_name." ".$user->last_name;
			}
			
			$functie = $item['bestuursfunctie'];
			if (strlen($functie) > 0) $line .= " ({$functie})";
			$ret[] = $line;


		}
		return $ret;
	}
	
	/*
	 * getFullList
	 * 
	 * returns : de volledige lijst van alle bestuursleden (als array)
	 *   voor elk item wordt een array bijgehouden met daarin id, voornaam, familienaam, phone, gsm, email
	 * 
	 */
	 public static function getFullList()
	 {
	 	$ret = null;
	 	$bestuur = Bestuur::all()->sortby('sortnr');
		foreach( $bestuur AS $item)
		{
			$temp = null;
			$temp['id'] = $item->id;
			$temp['first_name'] = $item->user->first_name;
			$temp['last_name'] = $item->user->last_name;
			$extrafull = UserExtra::where('user_id',$item->user->id)->get();
			$extra = $extrafull[0];
			$temp['phone'] = $extra->phone;
			$temp['gsm'] = $extra->gsm;
			$temp['email'] = $item->user->email;
			$temp['bestuursfunctie'] = $item->bestuursfunctie;
			$ret[] = $temp;
		}
		return $ret;
	 }
	 
	 /*
	  * getFullListTableHeader
	  * 
	  * we maken hier de header voor de tabel (FullList) van het bestuur
	  *     Hou rekening met 'ingelogd' of niet
	  *               en met 'admin' of niet
	  * 
	  * @return : een array met de strings (titels) voor de header
	  */
	  public static function getFullListTableHeader()
	  {
	  	$isAdmin = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
		$aangemeld = Sentry::check();
		
		$ret = null;
		
		if ($isAdmin) $ret[] = "";
		$ret[] = "voornaam";
		$ret[] = "familienaam";
		if ($aangemeld)
		{
			$ret[] = "telefoon";
			$ret[] = "gsm";
			$ret[] = "e-mail";
		}
		$ret[] = "functie";
		if ($isAdmin) $ret[] = "";
		return $ret;
	  }

      /*
	   * getFullListRow
	   * 
	   * we maken de volledige rij als een string met de <tr>'s
	   * 
	   * @args : element is een array met alle gegevens om te kunnen de rij opbouwen (aangemaakt in getFullList)
	   * @return : een string met de volledige rij in HTML
	   */
	  public static function getFullListRow($element)
	  {
	    $isAdmin = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
		$aangemeld = Sentry::check();
	   	
		$ret = null;
		if ($isAdmin) $ret = "<td>".AppHelper::makeUpDown('bestuur', $element['id'])."</td>";
		$ret .= "<td>{$element['first_name']}</td>";
		$ret .= "<td>{$element['last_name']}</td>";	
		if ($aangemeld)
		{
			$ret .= "<td>{$element['phone']}</td>";
			$ret .= "<td>{$element['gsm']}</td>";
			$ret .= "<td>{$element['email']}</td>";
		}	
		$ret .= "<td>{$element['bestuursfunctie']}</td>";
		if ($isAdmin) $ret .= "<td>".AppHelper::makeEditButtons('bestuur', $element['id'])."</td>";
		return $ret;
	  } 

	  /*
	   * moveItem($id, $direction)
	   * 
	   * @purpose : voor de bestuursleden ga naar boven of beneden
	   * 
	   * @args : 
	   *    id - de id van dit item in tabel bestuur
	   *    direction - up of down
	   * @return : success
	   */
	   public static function moveItem($id, $direction)
	   {
	   		$item = DB::table('bestuurs')->where('id', $id)->get();
			$sortnr = $item[0]->sortnr;
			
			if ($direction == 'up')
			{
				if ($sortnr == 1) return;
				// zoek de vorige
				$vorige = DB::table('bestuurs')->where('sortnr', ($sortnr-1))->get();
				// verwissel de 2
				DB::table('bestuurs')->where('id', $id)->update(array( 'sortnr' => $sortnr-1));
				DB::table('bestuurs')->where('id', $vorige[0]->id)->update(array( 'sortnr' => $sortnr));
			}
			
			if ($direction == 'down')
			{
				// Zoek het hoogste sortnr
				$max_sortnr = DB::table('bestuurs')->max('sortnr');
				// Als het sortnr >= max_sortnr --> return
				if ($sortnr >= $max_sortnr) return;
				
				// zoek het volgend item ( dus met sortnr+1)
				$volgend = DB::table('bestuurs')->where('sortnr', ($sortnr+1))->get();
				// verwissel de sortnrs van deze items
				DB::table('bestuurs')->where('id', $id)->update( array( 'sortnr' => $sortnr+1));
				DB::table('bestuurs')->where('id', $volgend[0]->id)->update( array('sortnr' => $sortnr));
			}
			return;
	   }

		/*
		 * getFullname
		 * 
		 * @purpose : zoek in users de voor- en familienaam van dit bestuurslid
		 * 
		 * @args :
		 *    - user_id : het veld user_id uit de tabel Bestuur van deze entry
		 * 
		 * @returns : een string met de volledige naam
		 */

		public static function getFullname($user_id)
		{
			$user = User::find($user_id);
			$naam = $user->first_name." ".$user->last_name;
			return $naam;
		}
}