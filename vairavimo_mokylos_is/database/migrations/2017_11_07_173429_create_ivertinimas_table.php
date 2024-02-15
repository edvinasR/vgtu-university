<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIvertinimasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ivertinimai', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ivertinimas');
            $table->text('aprasymas');
            $table->integer('mokinys')->unsigned();
            $table->integer('paskaita')->unsigned();
            $table->foreign('mokinys')->references('id')->on('mokiniai')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('paskaita')->references('id')->on('paskaitos')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('ivertinimai');
    }
}
