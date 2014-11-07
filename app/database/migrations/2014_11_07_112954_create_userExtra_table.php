<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserExtraTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('userExtra', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->date('birthdate')->nullable();
			$table->string('street')->nullable();
			$table->string('housenr',10)->nullable(); 
			$table->string('box',10)->nullable();
			$table->string('zip',10)->nullable();
			$table->string('country',20)->nullable();
			$table->string('phone',20)->nullable();
			$table->string('gsm',20)->nullable();
			$table->string('workplace')->nullable();
			$table->string('position')->nullable();
			$table->enum('title', array('juffrouw', 'Mevrouw', 'De Heer', 'Apr', 'dr'));
			$table->string('diploma')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('userExtra');
	}

}
