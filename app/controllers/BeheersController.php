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
		print('De initialisatie moet je nog uitvoeren [BeheersController@init]');
	}

}