<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGrupiuPaskaitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupiu_paskaitos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('paskaita')->unsigned();
            $table->integer('grupe')->unsigned();
            $table->foreign('paskaita')->references('id')->on('paskaitos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('grupe')->references('id')->on('KET_grupes')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('grupiu_paskaitos');
    }
}
