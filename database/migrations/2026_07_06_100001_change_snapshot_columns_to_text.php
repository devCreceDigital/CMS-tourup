<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trip_bookings', function (Blueprint $table) {
            $table->text('pricing_group_snapshot')->nullable()->change();
            $table->text('extras_snapshot')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('trip_bookings', function (Blueprint $table) {
            $table->string('pricing_group_snapshot')->nullable()->change();
            $table->string('extras_snapshot')->nullable()->change();
        });
    }
};
