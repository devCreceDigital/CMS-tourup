<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'booking_enabled'],
            ['value' => 'true', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    public function down()
    {
        DB::table('settings')->where('key', 'booking_enabled')->delete();
    }
};
