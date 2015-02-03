<?php

class DocumentsController extends \BaseController {

	/**
	 * Display a listing of documents
	 *
	 * @return Response
	 */
	public function index()
	{
		$documents = Document::all();

		return View::make('documents.index', compact('documents'));
	}

	/**
	 * Show the form for creating a new document
	 *
	 * @return Response
	 */
	public function create($rubriek){
		return View::make('documents.create', array('rubriek' => $rubriek));
	}

	public function spaar()
	{
		die("spaar ...");
	}
	/**
	 * Store a newly created document in storage.
	 *
	 * @return Response
	 */
	public function store()
	{ 
		$data = Input::all();
		// als er een newTitle is (trim!), dan wordt dit de title
		if (strlen(trim($data['newtitle'])) > 0){
			$data['title'] = trim($data['newtitle']); // The new title is the new one
		}		
		
		// De datum herformatteren
		$date = $data['date'];
		if ($date == null) $data['date'] = "0000-00-00";
		else {
			$data['date'] = AppHelper::formatEuropean2UTC($date);
		}		
		// Er moet ofwel een url ofwel een bestand zijn ...
		$url = $data['url'];
		$urlvol = strlen($url) > 0;
		
		if (Input::file('file') == null){
			$local = null;
			$localvol = 0;
		} else {
			$local = Input::file('file')->getClientOriginalName();
			$localvol = strlen($local) > 0;			
		}

		$xor = $urlvol ^$localvol;
		
		if ($xor){
			if ($localvol){
				// het bestand moet je nu uploaden en in de url plaatsen
				//$extension = AppHelper::getExtension($local);
				$extension = File::extension($local);

				$filename = md5(uniqid()) . "." . $extension;
				$directory = 'docs/';
				$moved = Input::file('file')->move($directory,$filename);
//				$newurl = public_path()."/".$filename;
$newurl = "public/docs/".$filename;
				$data['url'] = $newurl;
			}
		} else {
			// plaats foutmelding dat je niet beide kan kiezen ... maar 1 mogelijk
			die("fout niet beide");
		}		
		
		$validator = Validator::make($data, Document::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		
		
		$doc = new Document();
		$doc->title = $data['title'];
		$doc->description = $data['description'];
		$doc->url = $data['url'];
		$doc->date = $data['date'];
		$doc->sortnr = Document::berekenSortnr($data);
		
		if ($data['file'] != null){
			$filename = $data['file']->getClientOriginalName();
		} else {
			$filename = "";
		}
		$doc->localfilename = $filename;
		$doc->author = $data['author'];

		if (isset($data['alwaysvisible']))
		{
			$doc->alwaysvisible =  1;
		} else {
			$doc->alwaysvisible = 0;
		}
		$doc->type = $data['rubriek'];
		$date = new DateTime;
		$doc->created_at = $date;
		$doc->updated_at = $date;		


		// Als dit geen nieuw artikel is .... pas dan de sortnrs aan!
		// Is dit geen nieuw artikel?
		if ($data['title'] != $data['newtitle']){
			// hernummer alle items vanaf het item met sortnr == $doc->sortnr (maar niet deze nieuwe!)
			// Daarom halen we nu alle items op, gerangschikt volgens sortnr en groter of gelijk aan sortnr
			$thisSortnr = $doc->sortnr;
			$items = DB::table('documents')->where('type', $data['rubriek'])->where('sortnr','>=',$thisSortnr)->orderBy('sortnr')->get();
			$currentSortnr = $thisSortnr+1;
			foreach($items AS $item)
			{
				$id = $item->id;
				DB::table('documents')->where('id', $id)->update(array('sortnr' => $currentSortnr));
				$currentSortnr++;
			}
		}
		$ret = $doc->save();
		return Redirect::route('volledigelijst', array('rubriek' => $data['rubriek'], 'title' => 'leeg'));
	}

	/**
	 * Display the specified document.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$document = Document::findOrFail($id);

		return View::make('documents.show', compact('document'));
	}

	/**
	 * Show the form for editing the specified document.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$document = Document::find($id);

		return View::make('documents.edit', compact('document'));
	}


	/**
	 * Update the specified document in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{ 
		$document = Document::findOrFail($id);
		
		$current = Input::all();

		if (strlen(trim($current['newtitle'])) > 0){
			$current['title'] = trim($current['newtitle']); // The new title is the new one
		}
		
		// de datum herformatteren
		$date = $current['date'];
		if ($date == null) $current['date'] = "0000-00-00";
		else {
			$current['date'] = AppHelper::formatEuropean2UTC($date);
		}
		
		// url en file
		// Als er een file is gekozen --> uploaden naar lokale file en dan url geven naar deze
		$url = $current['url'];
		$urlvol = strlen($url) > 0;
		
		if (Input::file('file') == null){
			$local = null;
			$localvol = 0;
		} else {
			$local = Input::file('file')->getClientOriginalName();
			$localvol = strlen($local) > 0;			
		}

		$xor = $urlvol ^$localvol;
		
		if ($xor){
			if ($localvol){
				// het bestand moet je nu uploaden en in de url plaatsen
				//$extension = AppHelper::getExtension($local);
				$extension = File::extension($local);

				$filename = md5(uniqid()) . "." . $extension;
				$directory = 'docs/';
				$moved = Input::file('file')->move($directory,$filename);
//				$newurl = public_path()."/".$filename;
$newurl = "public/docs/".$filename;
				$current['url'] = $newurl;
			}
		} else {
			// plaats foutmelding dat je niet beide kan kiezen ... maar 1 mogelijk
			die("fout niet beide");
		}		
		
		if (isset($current['alwaysvisible'])) $current['alwaysvisible'] = 1; else $current['alwaysvisible'] = 0;		
		$validator = Validator::make($current, Document::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$document->update($current);

		return Redirect::route('volledigelijst', array('rubriek' => $document->type, 'title' => 'leeg'));

//		return Redirect::route('documents.index');
	}

	/**
	 * Remove the specified document from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, $rubriek)
	{
		$ditItem = DB::table('documents')->find($id);
		$url = $ditItem->url;

//		die("<p />[DocumentsController@destroy] -- ($id, $rubriek) file = {$url}");
		// Na het vernietigen moeten we 2 zaken uitvoeren
		Document::destroy($id);
		
		// Ten eerste : de sortnrs hernummeren!
		//    Het eenvoudigste hier is om gewoon de (geordende) rij van deze rubriek te hernummeren
		$items = DB::table('documents')->where('type', $rubriek)->orderBy('sortnr')->get();
		$currentSortnr = 1;
		foreach($items AS $item)
		{
			DB::table('documents')->where('id', $item->id)->update(array('sortnr' => $currentSortnr));
			$currentSortnr++;
		}
		// Ten tweede : het artikel zelf vernietigen (in public/docs)
		File::delete($url);

		return Redirect::route('volledigelijst', array('rubriek' => $rubriek, 'title' => 'leeg'));
	}
	
	/*
	 * volledigelijst
	 * 
	 * @purpose : toont de lijst van documenten, afhankelijk van de argumenten
	 * @args :
	 *    - rubriek : is eigenlijk het soort document (vb. novorming, interessante links ...)
	 *    - title : is eigenlijk de rubriek die getoond wordt
	 *        ( als null , dan wordt de volledige lijst getoond - )
	 *        LET HIER OP : als null en toch een soort document met rubrieken, dan moet je de rubrieken afzonderlijk tonen !!!!
	 * 
	 * @return de view
	 */
	public function volledigelijst($rubriek, $title)
	{

		// Hier halen we de documenten op
		//    Als er een title is
		if ($title != 'leeg')
		{
			$documenten = Document::whereRaw('type = ? and title = ?', array($rubriek, $title))->orderBy('sortnr')->get();
		} else {
			$documenten = Document::where('type', $rubriek)->orderBy('sortnr')->get();
		}	
		return View::make('documents.index')->with('documenten', $documenten)->with('rubriek', $rubriek)->with('title', $title);
	}

}
