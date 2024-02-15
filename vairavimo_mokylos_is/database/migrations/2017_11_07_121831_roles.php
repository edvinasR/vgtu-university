<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Roles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	
    	Schema::create('teises', function (Blueprint $table) {
    		$table->increments('id');
    		$table->string('pavadinimas');
    		$table->boolean('tvarkyti_mokiniu_informacija');
    		$table->boolean('tvarkyti_instruktorius');
    		$table->boolean('tvarkyti_grupes');
    		$table->boolean('tvarkyti_teorines_paskaitas');
    		$table->boolean('tvarkyti_pazymius');
    		$table->boolean('tvarkyti_praktines_paskaitas');
    		$table->boolean('rasyti_ivertinimus_mokiniui');
    	});
    	
    	
    
        Schema::create('users', function (Blueprint $table) {
        	$table->increments('id');
        	$table->string('name');
        	$table->string('surename');
        	$table->string('email')->unique();
        	$table->string('password');
        	$table->integer('teises_FK')->unsigned();
        	$table->rememberToken();
        	$table->timestamps();
        	$table->foreign('teises_FK')->references('id')->on('teises');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::dropIfExists('users');
    	Schema::dropIfExists('teises');
    }
}
