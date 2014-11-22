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
	        'first_name' => 'johan',
	        'last_name' => 'caluAdmin',
	    ));
		
		Sentry::getUserProvider()->create(array(
		  'email'    => 'johan.calu@telenet.be',
		  'password' => 'wvtvcalu',
		  'activated' => 1,
	        'first_name' => 'johan',
	        'last_name' => 'caluSecretary',		  
		));		

	    Sentry::getUserProvider()->create(array(
	        'email'    => 'johan@johancalu.be',
	        'password' => 'wvtvcalu',
	        'activated' => 1,
	        'first_name' => 'johan',
	        'last_name' => 'caluUser',	        
	    ));
	}

}
