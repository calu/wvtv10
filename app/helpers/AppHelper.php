<?php

class AppHelper {
	
	public static function getShortlist($rubriek)
	{
		if (Sentry::check())
		{
			$currentUser = Sentry::getUser()->id;
			$urlprofiel = url('changeprofile', $parameters = array('id' => $currentUser));
		}
		

		switch ($rubriek)
		{
			case 'bestuur' :
				$ret = Bestuur::getShortlist();
				break;
			case 'profiel' :
				$ret[] = "<a href = '$urlprofiel'>wijzig je profiel</a>";
				$ret[] = "wachtwoord wijzigen";
				break;
			case 'navorming' :
			case 'transfusie' :
				$titels = DB::select("SELECT DISTINCT(title) FROM documents WHERE type='{$rubriek}' ORDER BY sortnr");
				$aantalTitels = sizeof($titels);
				$max = ($aantalTitels > 4)? 4 : $aantalTitels;

				for ($i = 0; $i < $max; $i++)
				{
					$ret[] = $titels[$i]->title;
				}
				break;
			case 'links' :
				$titels = DB::select("SELECT title, url FROM documents where type='{$rubriek}' ORDER BY sortnr");
				$aantalTitels = sizeof($titels);
				$max = ($aantalTitels > 4)? 4 : $aantalTitels;
				
				for ($i=0; $i < $max; $i++)
				{
					$temp['title'] = $titels[$i]->title;
					$temp['url'] = $titels[$i]->url;
					$ret[] = $temp;
				}
				break;
			default :
				print("<br />[Apphelper/getShortList] deze rubriek {$rubriek} is nog niet geïmplementeerd");
				die(" ##### tot hier");
		}
		return $ret;
	}
	
	public static function getFullListRow($rubriek, $element)
	{
		switch($rubriek)
		{
			case 'bestuur' :
				$ret = Bestuur::getFullListRow($element);
				break;
			default :
				die("<br />[AppHelper@getFullListRow] deze rubriek {$rubriek} is nog niet geïmplementeerd");
							
		}
		return $ret;
	}
	
	/*
	 * makeUpDown
	 * 
	 * Hier maken we de HTML code (als string) die we zullen invoegen in de tabellen als er een up-down link moet komen
	 * 
	 * @arg :
	 *   - de rubriek  
	 *   - de id in de tabel van dit item
	 * @ret : de HTML string met als inhoud de up down arrows
	 */
	public static function makeUpDown($rubriek, $id)
	{
		$urlup = url('arrow', $parameters = array('id' => $id, 'rubriek' => $rubriek, 'direction' => 'up'));
		$urldown = url('arrow', $parameters = array('id' => $id, 'rubriek' => $rubriek, 'direction' => 'down'));
		
		$imgup = HTML::image('img/up.png');
		$imgdown = HTML::image('img/down.png');
		$ret = "<a href='$urlup' rel='tooltip'>{$imgup}</a>";
		$ret .= "<a href='$urldown' rel='tooltip'>{$imgdown}</a>";
		return $ret;
	}
	
	/*
	 * makeEditButtons
	 * 
	 * @args :
	 *   - de rubriek
	 *   - de id in de tabel van dit item
	 * 
	 * @ret de HTML string met als inhoud de edit en delete buttons
	 * 
	 */
	 public static function makeEditButtons($rubriek, $id)
	 {
	 	$urledit = url('edit', $parameters= array('id' => $id, 'rubriek' => $rubriek));
		$urldelete = url('delete', $parameters = array('id'=>$id, 'rubriek' => $rubriek));
		$ret = "<a href='$urledit' rel='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
		$ret .= " <a href='$urldelete' rel='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
		return $ret;
	 }
	 
	 /*
	  * moveItem
	  * 
	  * @purpose : wordt gebruikt om in de volledige lijsten met de pijltjes omhoog en omlaag te navigeren
	  * 
	  * We moeten hier onderscheid maken voor 2 mogelijke types :
	  *    1. Een volledige lineaire lijst ( bestuur, links, overheidspublicaties )
	  *    2. Een lijst met onderscheiden hoofdingen ( navorming, transfusie, documentatie,  )
	  * 
	  *    In geval 2 moet je kunnen de hoofdingen verplaatsen (waardoor ook de inhoud ervan verschuift)
	  * 
	  * @args :
	  *    id : de identificatie van dit item in de lijst
	  *    rubriek : de rubriek 
	  *    direction : up of down
	  *    isTitel : (niet verplicht - maar dan "neen") "ja" als het een titel is
	  * 
	  * @returns : success
	  */
	  public static function moveItem($id, $rubriek, $direction, $isTitle = "neen")
	  {
	  	switch ($rubriek)
		{
			case 'bestuur' :
				// Dit is een speciaal geval en voeren we dus uit in model Bestuur
				return Bestuur::moveItem($id, $direction);
				$type = 1;
				break;
			case 'navorming':
			case 'links' :
			case 'transfusie' :
				return Document::moveItem($id, $direction, $rubriek, $isTitle);
				break;
			default : 
				die("[AppHelper::moveItem] deze rubriek {$rubriek} is nog niet geïmplementeerd");
		}
			
	  }
	  
	  /*
	   * getRubriekpointer
	   * 
	   * @purpose : aan de hand van de rubriek naam, maken we nu de 'rubriek' pointer
	   * 
	   * @args : de naam van de rubriek
	   * @return : de rubriek pointer
	   * 
	   */
	   public static function getRubriekpointer($rubriek)
	   {
	   	switch( $rubriek)
		{
			case 'bestuur' : $ret = 'bestuurs'; break;
			case 'navorming' : $ret = 'navorming'; break;
			case 'links' : $ret = 'links'; break;
			case 'transfusie' : $ret = 'transfusie'; break;
			default : die("[AppHelper::getRubriekpointer] { $rubriek } nog niet geïmplementeerd");
		}
		return $ret;
	   }

	/*
	 * enum_to_array
	 * 
	 * @purpose : voor de enum velden in de databank, halen we hier de onderscheiden waarden op
	 * 
	 * @args :
	 *   - table : de tabel waaruit we het enum veld zullen halen
	 *   - field : het veld dat als enum staat gedefinieerd
	 * 
	 * @returns : een array met de onderscheiden waarden in de enum van de tabel
	 */
	public static function enum_to_array($table, $field)
	{
		$result = DB::select("SHOW FIELDS FROM {$table} LIKE '{$field}'");
		$resultvalue = $result[0]->Type;
		preg_match('/enum\((.*)\)$/', $resultvalue, $matches);
		$enum = array();
		foreach( explode(',', $matches[1]) AS $value)
		{
			$v = trim( $value, "'");
			$enum = array_add($enum, $v, $v);
		}
		return $enum;
	}	
	
	/*
	 * getFullList
	 * 
	 * @purpose : haal de lijst op met alle entries voor deze rubriek - 
	 *    let op : als title leeg is wordt alles opgehaald, anders enkel de entries met deze title 
	 * @args :
	 *   - rubriek : is het item ( vb. navorming, links, ....)
	 *   - title : is de 'rubriek' dat we willen zien - indien 'leeg' dan alles ophalen
	 * @return : array van objecten die de inhoud bezitten
	 * 
	 */ 
	 public static function getFullList( $rubriek, $title)
	 {

	 	switch($rubriek)
		{
			case 'navorming' :
			case 'transfusie' :
				if ($title == 'leeg')
				{
					if (Sentry::check())
					{
						$ret = DB::table('documents')
								->where('type', $rubriek)
								->orderBy('title','sortnr')
								->get();						
					} else {
						$ret = DB::table('documents')
								->where('type', $rubriek)
								->where('alwaysvisible', 1)
								->orderBy('title','sortnr')
								->get();
					}					
				}
				else {
//					if (Sentry::check())
//					{
						$ret = DB::table('documents')
								->where('type', $rubriek)
								->where('title', $title)
								->orderBy('sortnr')
								->get();						
/*					} else {
						$ret = DB::table('documents')
								->where('type', $rubriek)
								->where('title', $title)
								->where('alwaysvisible', 1)
								->orderBy('sortnr')
								->get();						
					}
 */
				}
				break;
			case 'links' :
				$ret = DB::table('documents')
						->where('type', $rubriek)
						->orderBy('sortnr')
						->get();
				break;
			default :
				die("[AppHelper/getFullList] deze functie werd nog niet geïmplementeerd voor {$rubriek}");
		}
		return $ret;
	 }
	 
	 /*
	  * date conversion UTC2European
	  * 
	  * @purpose : change the UTC date (as in database) to the European representation ( 2nd argument)
	  * @args :
	  *    - date : dateFormat
	  *    - format : dateFormat ()
	  * @return : date in format from 2nd arg
	  */
	  public static function formatUTC2European($datestring, $format = 'j-m-Y')
	  {
	  		$date = new DateTime($datestring);
	  		return $date->format($format);
	  }
	  
	  /*
	   * date conversion European2UTC
	   * 
	   * @purpose : change the date format back to the UTC date - to write it in the database
	   * @args :
	   *    - date : string in European format
	   * @return : date in UTC format
	   * 
	   */
	  public static function formatEuropean2UTC($datestring)
	  {
	  	$date = new DateTime($datestring);
		return $date->format('Y-m-j');
	  }
	  
	  /*
	   * getExtension : get de ext value of the file xxxxx.ext
	   * return : ext
	   * 
	   */
	  public static function getExtension($filename)
	  {
	  	// zoek de plaats van de laatste .
	  	$pos = strrpos($filename,'.');
		$ret = substr($filename, $pos+1);
		return $ret;
	  }
	  
	  
}

?>