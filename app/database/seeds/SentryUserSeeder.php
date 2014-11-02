<?php

class SentryUserSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->delete();

		Sentry::getUserProvider()->create(array(
	        'email'    => 'johan.calu@gmail.com',
	        'password' => 'wvtvcalu',
	        'activated' => 1,
	    ));
		
		Sentry::getUserProvider()->create(array(
		  'email'    => 'johan.calu@telenet.be',
		  'password' => 'wvtvcalu',
		  'activated' => 1,
		));		

	    Sentry::getUserProvider()->create(array(
	        'email'    => 'johan@johancalu.be',
	        'password' => 'wvtvcalu',
	        'activated' => 1,
	    ));
	}

}
