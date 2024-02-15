<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FileChunks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('file_chunks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('file_id');
            $table->unsignedInteger('cloud_service');
            $table->string('id_on_cloud');
            $table->string('name');
            $table->unsignedInteger('order');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->foreign('cloud_service')->references('id')->on('cloud_services')->onDelete('cascade');
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
        Schema::dropIfExists('file_chunks');
    }
}
