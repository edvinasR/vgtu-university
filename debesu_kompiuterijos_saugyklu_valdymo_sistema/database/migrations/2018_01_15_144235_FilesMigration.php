<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FilesMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storage_service_id')->nullable();
            $table->unsignedInteger('storage_service')->nullable();
            $table->unsignedInteger('size')->default(0);
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->string('extension');
            $table->tinyInteger('chunked')->default(0);
            $table->string('dowload_link')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('storage_service')->references('id')->on('cloud_services')->onDelete('cascade');
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
        Schema::dropIfExists('files');
    }
}
