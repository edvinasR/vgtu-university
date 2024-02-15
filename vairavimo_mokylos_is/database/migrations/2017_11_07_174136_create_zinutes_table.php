<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZinutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zinutes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tema');
            $table->tinyInteger('perskaitytas');
            $table->text('zinute');
            $table->integer('instruktorius')->unsigned();
            $table->integer('mokinys')->unsigned();
            $table->foreign('mokinys')->references('id')->on('mokiniai')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('zinutes');
    }
}
