<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("game_result", function (Blueprint $table) {
            $table->uuid('id')->primaty()->nullable(false);
            $table->uuid('quiz_id')->nullable(false);
            $table->uuid('user_id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->integer('result')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_result');
    }
};
