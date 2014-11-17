<?php

class AppHelper {
	
	public static function getShortlist($rubriek)
	{
		switch ($rubriek)
		{
			case 'bestuur' :
				$ret = Bestuur::getShortlist();
				break;
			case 'profiel' :
				$ret[] = "profiel bewerken";
				$ret[] = "wachtwoord wijzigen";
				break;
			case 'navorming' :
				$ret[] = "1";
				$ret[] = "2";
				break;
			default :
				print("<br /> deze rubriek {$rubriek} is nog niet geïmplementeerd");
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
	  * 
	  * @returns : success
	  */
	  public static function moveItem($id, $rubriek, $direction)
	  {
	  	switch ($rubriek)
		{
			case 'bestuur' :
				// Dit is een speciaal geval en voeren we dus uit in model Bestuur
				return Bestuur::moveItem($id, $direction);
				$type = 1;
				break;
			default : 
				die("[AppHelper::moveItem] deze rubriek {$rubriek} is nog niet geïmplementeerd");
		}

		$item = DB::table($table)->where('id', $id)->get();
		$sortnr = $item[0]->sortnr;
		
		if ($direction == 'up')
		{
			die("TODO [AppHelper:moveItem]");
			
			
		}
		
		if ($direction == 'down')
		{
			die("TODO [AppHelper:moveItem]");
		}
		var_dump($item); die('xx');
			
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
			default : die("[AppHelper::getRubriekpointer] { $rubriek } nog niet geïmplementeerd");
		}
		return $ret;
	   }
	 
}

?>