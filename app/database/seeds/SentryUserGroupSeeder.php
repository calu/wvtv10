<?php

class SentryUserGroupSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users_groups')->delete();

		$userUser = Sentry::getUserProvider()->findByLogin('johan@johancalu.be');
		$secretaryUser = Sentry::getUserProvider()->findByLogin('johan.calu@telenet.be');
		$adminUser = Sentry::getUserProvider()->findByLogin('johan.calu@gmail.com');

		$userGroup = Sentry::getGroupProvider()->findByName('Users');
		$secretaryGroup = SEntry::getGroupProvider()->findByName('Secretarys');
		$adminGroup = Sentry::getGroupProvider()->findByName('Admins');

	    // Assign the groups to the users
	    $userUser->addGroup($userGroup);
		
		$secretaryUser->addGroup($userGroup);
		$secretaryUser->addGroup($secretaryGroup);
		
	    $adminUser->addGroup($userGroup);
		$adminUser->addGroup($secretaryGroup);
	    $adminUser->addGroup($adminGroup);
	}

}
