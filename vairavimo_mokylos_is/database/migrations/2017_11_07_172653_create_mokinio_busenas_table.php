<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMokinioBusenasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mokinio_busenos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('teorinio_egzamino_ivertinimas')->nullable();
            $table->integer('praktinio_egzamino_ivertinimas')->nullable();
            $table->integer('mokinys')->unsigned();
            $table->foreign('mokinys')->references('id')->on('mokiniai')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('mokinio_busenos');
    }
}
