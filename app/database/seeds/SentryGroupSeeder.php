<?php

class SentryGroupSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('groups')->delete();

		Sentry::getGroupProvider()->create(array(
	        'name'        => 'Users',
	        'permissions' => array(
	            'admin' => 0,
	            'secretary' => 0,
	            'users' => 1,
	        )));

		Sentry::getGroupProvider()->create(array(
	        'name'        => 'Secretarys',
	        'permissions' => array(
	            'admin' => 0,
	            'secretary' => 1,
	            'users' => 1,
	        )));
			
		Sentry::getGroupProvider()->create(array(
	        'name'        => 'Admins',
	        'permissions' => array(
	            'admin' => 1,
	            'secretary' => 1,
	            'users' => 1,
	        )));
	}

}