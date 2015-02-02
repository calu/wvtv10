<?php

class Document extends \Eloquent {

	// Add your validation rules here
	public static $rules = array(
		 'title' => 'required',
		 'description' => 'required | min:2',
	);
	// author niet verplicht
	// 
	
	public static $messages = array(
		'required' => "Het trans('document.'.:attribute) veld moet ingevuld zijn",
	);

	// Don't forget to fill this array
	protected $fillable = array(
		'id','title','description','url','date','sortnr','localfilename','author','alwaysvisible','type','created_at',
		'updated_at'
	);
	protected $guarded = array();
 	
	/*
	 * moveItem($id, $direction, $rubriek, $isTitle)
	 * 
	 * @purpose : verplaats de items naar boven of beneden (volgens $direction), maar hou er rekening mee
	 *            dat de rubriek belangrijk is en dat je moet rekening
	 */
	public static function moveItem($id, $direction, $rubriek, $isTitle)
	{
		// veronderstelling : het sortnr is van 1 tot x genummerd - geordend per rubriek (title) voor elk type
		// haal nu alles op van de desbetreffende rubriek geordend per type
		
		// Haal het item met deze id
		//   bewaar het sortnr --> sortnrDitItem
		
		$ditItem = Document::find($id);
		
		$sortnrDitItem = $ditItem->sortnr;
		$ditItemTitel = $ditItem->title;
		
		
		// Als direction == up
		if ($direction == 'up')
		{
				
			//   Als sortnr == 1 --> return (wijzig niets want het staat al vooraan)
			if ($sortnrDitItem == 1) return;
			//   Als isTitle --> 
			if ($isTitle == 'yes')
			{
				// haal vorige titel ( vooral sortnr vorige belangrijk - en let op : is er wel een vorige?)
				//   haal eerst vorig item
				$vorigSortnr = $sortnrDitItem-1;
				$vorigItem = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $vorigSortnr))->get();
			
				
				$vorigeTitel = $vorigItem[0]->title;
				$hulpsortnr = $vorigSortnr - 1;
				$vorigeTitelItem = $vorigItem;	
						
				do {
					$zelfdetitel = true;
					if ($hulpsortnr <= 0) break;
					$vorigItem = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $hulpsortnr))->get();
					if ($vorigItem[0]->title == $vorigeTitel)
					{
						$vorigeTitelItem = $vorigItem;
						$hulpsortnr--;
					} else {
						$zelfdetitel = false;
					}
				} while ($zelfdetitel);
				
//				var_dump($vorigeTitelItem);
//				die(" vorig item (rubriek = {$rubriek} en sortnr = {$vorigSortnr}) - en $vorigeTitelItem[0]->id");
				//      haal volgende titel ( vooral sortnr volgende belangrijk - en let op : is er wel een volgende?)
				$maxSortnr = DB::table('documents')->where('type', $rubriek)->max('sortnr');
				$huidigeTitel = $ditItem->title;
				$hulpsortnr = $sortnrDitItem + 1;
				
				if ($hulpsortnr > $maxSortnr) $eindeDezeRubriekSortnr = $sortnrDitItem;
				else
					do{
						$zelfdetitel = true;
						
						if ($hulpsortnr >= $maxSortnr) break;
					
						$volgendItem = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $hulpsortnr))->get();
		
						if ($volgendItem[0]->title == $huidigeTitel)
						{
							$eindeDezeRubriekSortnr = $volgendItem[0]->sortnr;
							$hulpsortnr++;
						} else {
							$zelfdetitel = false;
							$hulpsortnr++;
						}
						
					} while ($zelfdetitel);
					
				// hier is vorige reeks en huidige reeks gekend		
				//   vorige reeks
				$startVorigSortnr = $vorigeTitelItem[0]->sortnr;
				$eindeVorigSortnr = $sortnrDitItem - 1;
								
				// maak nu een array van de id's van beide groepen
				for ($i=$startVorigSortnr; $i <= $eindeVorigSortnr; $i++)
				{
					$item = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $i))->get();
					$vorige[] = $item[0]->id;
				}
				// Zoek het einde van deze rubriek
				$laatste = Document::whereRaw('type= ? and sortnr = ?', array($rubriek, $sortnrDitItem))->max('sortnr');
				print("($rubriek, $id)<br />");
				var_dump($laatste);die("dfdsqfdsf");
				// maak nu de array van de id's van volgende
				for ($i=$sortnrDitItem; $i <= $eindeDezeRubriekSortnr; $i++)
				{
					$item = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $i))->get();
					$volgende[] = $item[0]->id;
				}
								
				$sortnr = $startVorigSortnr;
				foreach($volgende AS $id)
				{
					DB::table('documents')->where('id', $id)->update(array('sortnr'=> $sortnr));
					$sortnr++;
				}
				foreach($vorige AS $id)
				{
					DB::table('documents')->where('id', $id)->update(array('sortnr' => $sortnr));
					$sortnr++;
				}

			} else { //   Anders (als geen isTitle)
			   // wissel de huidige met de vorige, als de vorige nog tot deze rubriek behoort 
			   // het wisselen is enkel de sortnr's wisselen

			   if ($sortnrDitItem == 1) return;
			   $vorigSortnr = $sortnrDitItem - 1;
			   $vorigItem = $item = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $vorigSortnr))->get();				   
//			   if ($vorigItem[0]->title != $ditItemTitel) return;
			   DB::table('documents')->where('id', $ditItem->id)->update(array('sortnr' => $vorigSortnr));
			   DB::table('documents')->where('id', $vorigItem[0]->id)->update(array('sortnr' => $sortnrDitItem));		
			}
		} else { // Als direction == down
			// Haal het hoogste sortnr op
			$maxSortnr = DB::table('documents')->where('type', $rubriek)->max('sortnr');
			// Als het sortnr van het huidige item == maxSortnr --> return
			if ($sortnrDitItem == $maxSortnr) return;			
			if ($isTitle == 'yes')
			{
				// is dit de laatste Titel?
				$ditItemTitle = $ditItem->title;
				$runningSortnr = $sortnrDitItem+1;
				$verderdoen = true;
				$huidige[] = $ditItem->id;
				do {
					if ($runningSortnr > $maxSortnr) break;
					else {
						$item = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $runningSortnr))->get();						
						if ($item[0]->title != $ditItemTitle) break;
						$huidige[] = $item[0]->id;
						$runningSortnr++;
					}
				} while ($verderdoen);
				if ($runningSortnr >= $maxSortnr) return;
				
				// zoek nu de items met volgende titel
				$volgendeTitel = $item[0]->title;
				$volgende[] = $item[0]->id;
				$verderdoen = true;
				do{
					$runningSortnr++;
					if ($runningSortnr > $maxSortnr) break;
					
					$item = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $runningSortnr))->get();
					if ($item[0]->title != $volgendeTitel) break;
					$volgende[] = $item[0]->id;
				} while ($verderdoen);
				
				// dit is niet de laatste rubriek --> we verwisselen dus
				$sortnr = $sortnrDitItem;
				foreach($volgende AS $id)
				{
					DB::table('documents')->where('id', $id)->update(array('sortnr' => $sortnr));
					$sortnr++;
				}
				foreach($huidige AS $id)
				{
					DB::table('documents')->where('id', $id)->update(array('sortnr' => $sortnr));
					$sortnr++;
				}
							

			} else {
				// Hier moeten we gewoon deze verwisselen met de volgende - let op als je aan het einde van deze rubriek bent!
				$maxSortnr = DB::table('documents')->where('type', $rubriek)->max('sortnr');
				$volgendSortnr = $sortnrDitItem +1;
				if ($volgendSortnr > $maxSortnr) return;
				$volgendItem = $item = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $volgendSortnr))->get();
//				if ($volgendItem[0]->title != $ditItemTitel) return;
			   DB::table('documents')->where('id', $ditItem->id)->update(array('sortnr' => $volgendSortnr));
			   DB::table('documents')->where('id', $volgendItem[0]->id)->update(array('sortnr' => $sortnrDitItem));				
			}
		}
	}

    /*
	 * berekenSortnr($data)
	 * 
	 * @purpose :
	 *    Het juiste sortnr wordt berekend.
	 *    Als dit een nieuwe rubriek is, dan krijgt dit item het hoogste sortnr + 1
	 *    Als dit een bestaande rubriek is, dan krijgt dit item het hoogste sortnr van deze rubriek + 1
	 *       en de overige rubrieken (vanaf dit sortnr) worden hernummerd
	 * 
	 * @params : data is een array met alle gegevens van dit nieuw item
	 * @return : het sortnr dat je hier moet invullen
	 */
    public static function berekenSortnr($data)
	{
		// Is dit een nieuwe rubriek?
		if ($data['title'] == $data['newtitle'])
		{
			// Een nieuwe rubriek, wordt onderaan in de reeks toegevoegd
			// Begin dus met het hoogste sortnr voor deze rubriek te zoeken
			$maxSortnr = DB::table('documents')->where('type', $data['rubriek'])->max('sortnr');
			return $maxSortnr+1;
		} else {
			// Deze rubriek bestaat reeds
			//   Zoek nu het hoogste sortnr in de reeks met deze 'title'
			$maxSortnr = DB::table('documents')->where('type', $data['rubriek'])->where('title', $data['title'])->max('sortnr');
			return $maxSortnr+1;
		}
	}

}