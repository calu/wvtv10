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
		$ditItemSortnr = $ditItem->sortnr;
		if ($direction == 'up'){
			if ($ditItemSortnr == 1) return; // je kan niet meer naar boven
			// is het een titel?
			if ($isTitle == 'yes'){
				// verschuif blokken
				self::moveUpBlok($ditItem,$rubriek);
			} else {
				// verschuif binnen dit blok
				self::moveUpInBlok($ditItem, $rubriek);
			}
		} else { // direction = down
			// Zoek het laatste item
			$maxSortnr = DB::table('documents')->where('type', $rubriek)->max('sortnr');
			if ($ditItemSortnr == $maxSortnr) return; // je kan niet meer verder naar beneden
			if ($isTitle == 'yes'){
				// verschuif blokken naar beneden
				self::moveDownBlok($ditItem, $rubriek);
			} else {
				// verschuif binnen het blok naar beneden
				self::moveDownInBlok($ditItem, $rubriek);
			}
		}
	}

	public static function moveUpBlok($ditItem,$rubriek){
		// Haal de volledige huidige blok op (gesorteerd volgens sortnr)
		$huidigBlok = DB::table('documents')->whereRaw('type = ? and title = ?', array($rubriek, $ditItem->title))->orderBy('sortnr')->get();

		// Haal de volledige vorige blok op (gesorteerd volgens sortnr)
		$vorigItem = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $ditItem->sortnr - 1))->get();
		$vorigeTitel = $vorigItem[0]->title;

		$vorigBlok = DB::table('documents')->whereRaw('type = ? and title = ?', array($rubriek, $vorigeTitel))->orderBy('sortnr')->get();
		
		// haal nu het sortnr om te starten
		$sortnr = $vorigBlok[0]->sortnr;
		// hernummer nu de blokken
		$sortnr =self::hernummer($huidigBlok,$sortnr);
		$sortnr = self::hernummer($vorigBlok,$sortnr);

	}

	public static function moveDownBlok( $ditItem, $rubriek){
		// Haal de huidige blok op (gesorteerd volgens sortnr)
		$huidigBlok = DB::table('documents')->whereRaw('type = ? and title = ?', array($rubriek, $ditItem->title))->orderBy('sortnr')->get();
		// Is dit de laatste blok?
		//   Haal het laatste 'sortnr' in dit blok op
		$laatsteSortnr = $huidigBlok[sizeof($huidigBlok)-1]->sortnr;
		//   Haal het hoogste sortnr voor deze rubriek
		$maxSortnr = DB::table('documents')->where('type', $rubriek)->max('sortnr');

		// Als het hoogste sortnr = laatsteSortnr van het huidige blok ... dan moet je niets doen
		if ($laatsteSortnr == $maxSortnr) return;
		
		// Haal het volgende blok op (gesorteerd op sortnr)
		$volgendItem = Document::whereRaw('type = ? and sortnr = ?', array($rubriek, $laatsteSortnr+1))->get();
		$volgendeTitel = $volgendItem[0]->title;
		$volgendBlok = DB::table('documents')->whereRaw('type = ? and title = ?', array( $rubriek, $volgendeTitel))->orderBy('sortnr')->get();

		
		// haal nu het startnr om te starten
		$sortnr = $huidigBlok[0]->sortnr;
		$sortnr = self::hernummer($volgendBlok, $sortnr);
		$sortnr = self::hernummer($huidigBlok, $sortnr);	
	}

	public static function moveUpInBlok($ditItem, $rubriek)
	{
		// haal het volledige huidige blok op
		$huidigBlok = DB::table('documents')->whereRaw('type = ? and title = ?', array($rubriek, $ditItem->title))->orderBy('sortnr')->get();
		$eersteSortnr = $huidigBlok[0]->sortnr;

		// Als het sortnr van dit item = eersteSortnr --> je moet niets doen
		if ($ditItem->sortnr == $eersteSortnr) return;
		
		// Verwissel dit item met het vorige item = hernummer sortnr van beide items
		// Haal het vorige item op
		$vorigItem = Document::whereRaw('type = ? and sortnr = ?', array( $rubriek, $ditItem->sortnr - 1))->get();

		DB::table('documents')->where('id', $vorigItem[0]->id)->update(array('sortnr' => $ditItem->sortnr));
		DB::table('documents')->where('id', $ditItem->id)->update(array('sortnr' => $ditItem->sortnr-1));		
	}
	
	public static function moveDownInBlok($ditItem, $rubriek){
		// haal het volledige huidige blok op
		$huidigBlok = DB::table('documents')->whereRaw('type = ? and title = ?', array($rubriek, $ditItem->title))->orderBy('sortnr')->get();
		// haal het hoogste sortnr (dus laatste) in dit blok
		$laatsteSortnrInHuidigBlok = $huidigBlok[sizeof($huidigBlok)-1]->sortnr;
//		print("laatste sortnr in huidig blok = {$laatsteSortnrInHuidigBlok}<br />");		
		// Als het sortnr van dit item = laatste sortnr in huidig blok --> return (doe niets)
		if ($ditItem->sortnr == $laatsteSortnrInHuidigBlok) return;
		
		// Verwissel het sortnr van dit item met het sortnummer van het volgende item
		$volgendItem = Document::whereRaw('type = ? and sortnr = ?', array( $rubriek, $ditItem->sortnr+1))->get();
		
		DB::table('documents')->where('id', $volgendItem[0]->id)->update(array('sortnr' => $ditItem->sortnr));
		DB::table('documents')->where('id', $ditItem->id)->update(array('sortnr' => $ditItem->sortnr+1));
	}
	
	/*
	 * hernummer
	 * 
	 * @purpose : het volledig blok dat werd meegegeven en dat alle items met zelfde titel bevatten geordend volgens sortnr
	 *   worden hernummerd, beginnend met het sortnr meegegeven als 2de param
	 * @args :
	 *   - $blok : het blok (een array van objecten) dat moet hernummerd worden
	 *   - $sortnr : het sortnr waarmee we beginnen
	 * @return : het sortnr dat het volgende blok moet krijgen
	 */
	 public static function hernummer($blok, $sortnr)
	 {
	 	foreach($blok AS $item)
		{
			DB::table('documents')->where('id', $item->id)->update(array('sortnr' => $sortnr));
			$sortnr++;
		}
		return $sortnr;
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