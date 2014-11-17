<?php

class BestuursController extends \BaseController {

	/**
	 * Display a listing of bestuurs
	 *
	 * @return Response
	 */
	public function index()
	{
		$bestuurs = Bestuur::all();

		return View::make('bestuurs.index', compact('bestuurs'));
	}

	/**
	 * Show the form for creating a new bestuur
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('bestuurs.create');
	}

	/**
	 * Store a newly created bestuur in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Bestuur::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Bestuur::create($data);

		return Redirect::route('bestuurs.index');
	}

	/**
	 * Display the specified bestuur.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$bestuur = Bestuur::findOrFail($id);

		return View::make('bestuurs.show', compact('bestuur'));
	}

	/**
	 * Show the form for editing the specified bestuur.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$bestuur = Bestuur::find($id);
		return View::make('bestuurs.edit', compact('bestuur'));
	}

	/**
	 * Update the specified bestuur in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$bestuur = Bestuur::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Bestuur::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$bestuur->update($data);
		return Redirect::to('/volledigelijst/bestuur');

	}

	/**
	 * Remove the specified bestuur from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// Haal alle items - geordend volgens sortnr
		$bestuurlijst = Bestuur::all()->sortBy('sortnr');
		// Zoek het item met id
		$index = 0;
		$found = false;
		while ($index < sizeof($bestuurlijst) && !$found)
		{
			$item = $bestuurlijst[$index];
			if ($item->id == $id) { $found = true; break; }
			$index++;
		}
		$sortnr = $item->sortnr;
		// vervang de sortnrs van de volgende
		for ($i = $index+1; $i < sizeof($bestuurlijst); $i++)
		{
			// $bestuurlijst[$i]->sortnr = $sortnr++;
			$idtemp = $bestuurlijst[$i]->id;
			$item = Bestuur::findOrFail($idtemp);
			$item->sortnr = $sortnr++;
			$item->save();
		}
		Bestuur::destroy($id);
		return Redirect::to('/volledigelijst/bestuur');
	}

}
