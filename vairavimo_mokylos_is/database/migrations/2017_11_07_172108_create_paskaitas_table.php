<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaskaitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	
        Schema::create('paskaitos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pavadinimas');
            $table->string('vieta');
            $table->tinyInteger('praktine_paskaita');
            $table->dateTime('pradzia');
            $table->dateTime('pabaiga');
            $table->text('aprasymas');
            $table->integer('instruktorius')->unsigned();
            $table->integer('mokinys')->unsigned()->nullable();
            $table->foreign('instruktorius')->references('id')->on('instruktoriai')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('paskaitos');
    }
}
