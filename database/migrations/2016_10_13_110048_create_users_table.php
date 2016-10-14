<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('username')->primary();
            $table->bigInteger('credit')->default(0);
            $table->timestamps();
        });

        Schema::create('players', function (Blueprint $table) {
            $table->string('name')->primary();
            $table->unsignedInteger('division');
            $table->unsignedInteger('position');
            $table->unsignedInteger('points');
            $table->timestamps();
        });

        Schema::create('shares', function (Blueprint $table) {
            // No support for composed PKs in ORM so...
            $table->unsignedInteger('id')->primary();
            $table->string('player');
            $table->string('user');
            $table->unsignedInteger('amount')->default(1);
            $table->unsignedInteger('buy_price')->default(0);
            $table->unique(['player', 'user']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('players');
        Schema::dropIfExists('shares');
    }
}
