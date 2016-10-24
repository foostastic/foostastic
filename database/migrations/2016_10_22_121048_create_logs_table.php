<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_logs', function (Blueprint $table) {
            $table->increments('user_log_id');
            $table->string('username');
            $table->unsignedInteger('points')->default(0);
            $table->unsignedInteger('credit')->default(0);
            $table->timestamps();
            $table->index('username');
        });

        Schema::create('player_logs', function (Blueprint $table) {
            $table->increments('player_log_id');
            $table->string('name');
            $table->unsignedInteger('division');
            $table->unsignedInteger('position');
            $table->unsignedInteger('points');
            $table->unsignedInteger('share_points');
            $table->unsignedInteger('value');
            $table->timestamps();
            $table->index('name');
        });

        Schema::create('share_action_logs', function (Blueprint $table) {
            $table->increments('action_log_id');
            $table->string('player');
            $table->string('user');
            $table->string('action');
            $table->unsignedInteger('amount')->default(1);
            $table->unsignedInteger('action_price')->default(0);
            $table->timestamps();
            $table->index('player');
            $table->index('user');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_logs');
        Schema::dropIfExists('player_logs');
        Schema::dropIfExists('share_action_logs');
    }
}
