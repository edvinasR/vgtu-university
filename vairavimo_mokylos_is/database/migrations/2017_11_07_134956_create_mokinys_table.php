<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMokinysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mokiniai', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kategorija');
            $table->integer('grupe')->unsigned();
            $table->integer('vairavimo_instruktorius')->unsigned()->nullable();
            $table->integer('naudotojas')->unsigned();
            $table->foreign('naudotojas')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('grupe')->references('id')->on('ket_grupes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vairavimo_instruktorius')->references('id')->on('instruktoriai')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('mokiniai');
    }
}
