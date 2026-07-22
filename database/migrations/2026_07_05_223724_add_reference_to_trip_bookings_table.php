<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trip_bookings', function (Blueprint $table) {
            $table->string('reference', 36)->nullable()->after('id')->index();
            $table->integer('spots')->default(1)->after('reference');
            $table->text('pricing_group_snapshot')->nullable()->after('notes');
            $table->text('extras_snapshot')->nullable()->after('pricing_group_snapshot');
        });
    }

    public function down()
    {
        Schema::table('trip_bookings', function (Blueprint $table) {
            $table->dropColumn(['reference', 'spots', 'pricing_group_snapshot', 'extras_snapshot']);
        });
    }
};
