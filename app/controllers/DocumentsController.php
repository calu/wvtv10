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
	public function create()
	{
		return View::make('documents.create');
	}

	/**
	 * Store a newly created document in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Document::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Document::create($data);

		return Redirect::route('documents.index');
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

		$validator = Validator::make($data = Input::all(), Document::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$document->update($data);

		return Redirect::route('documents.index');
	}

	/**
	 * Remove the specified document from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Document::destroy($id);

		return Redirect::route('documents.index');
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
