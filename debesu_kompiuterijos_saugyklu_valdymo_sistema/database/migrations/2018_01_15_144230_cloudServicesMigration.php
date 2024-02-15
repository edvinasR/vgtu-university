<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CloudServicesMigration extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cloud_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('root_folder_id')->nullable();
            $table->longText('access_token')->nullable();
            $table->longText('refresh_token')->nullable();
            $table->string('identity')->nullable();
            $table->string('owner')->nullable();
            $table->string('logo');    
            $table->string('type');
            $table->integer('user_id')->unsigned();
            $table->bigInteger('free_storage')->unsigned()->nullable();
            $table->string('name');
            $table->tinyInteger('activated')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique('name');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cloud_services');
    }
 }
    

