<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('media_images', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('file_path');
            $table->string('alt_text')->nullable();
            $table->string('section')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media_images');
    }
};
