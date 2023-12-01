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
        Schema::create("question_table", function (Blueprint $table) {
            $table->uuid('id')->primaty()->nullable(false);
            $table->uuid('user_id')->nullable(false);
            $table->uuid('quiz_id')->nullable(false);
            $table->uuid('type_id')->nullable(false);
            $table->string('text')->nullable(false);
            $table->string('path');
            $table->string('link_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_table');
    }
};
