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
        Schema::table('itinerary_days', function (Blueprint $table) {
            $table->unique(['trip_id', 'day_number']);
        });
    }

    public function down()
    {
        Schema::table('itinerary_days', function (Blueprint $table) {
            $table->dropUnique(['trip_id', 'day_number']);
        });
    }
};
