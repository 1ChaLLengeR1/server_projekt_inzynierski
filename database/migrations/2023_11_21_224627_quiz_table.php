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
        Schema::create("quiz_table", function (Blueprint $table) {
            $table->uuid('id')->primaty()->nullable();
            $table->string('name');
            $table->string('description');
            $table->string('image_path');
            $table->string('link_image');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_table');
    }
};
