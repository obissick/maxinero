<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToCreated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_stats', function (Blueprint $table) {
            $table->index('created_at');
        });
        Schema::table('service_stats', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('server_stats', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
        Schema::table('service_stats', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
}
