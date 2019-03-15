<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('setting_id'); 
            $table->string('service_id');
            $table->integer('connections');
            $table->integer('total_connections');
            $table->integer('queries')->nullable();
            $table->timestamps();
            $table->foreign('setting_id')->references('id')->on('settings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_stats');
    }
}
