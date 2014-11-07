<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('documents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->text('description');
			$table->string('url');
			$table->date('date');
			$table->integer('sortnr');
			$table->string('localfilename')->nullable();
			$table->string('author')->nullable();
			$table->boolean('alwaysvisible');
			$table->enum('type',array('document', 'wetgeving', 'transfusie', 'links', 'navorming'));
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
		Schema::drop('documents');
	}

}
