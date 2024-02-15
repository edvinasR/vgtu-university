<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInstruktoriusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instruktoriai', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transporto_priemones_numeris');
            $table->text('telefonas');
            $table->integer('naudotojas')->unsigned()->nullable();
            $table->foreign('naudotojas')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('instruktoriai');
    }
}
