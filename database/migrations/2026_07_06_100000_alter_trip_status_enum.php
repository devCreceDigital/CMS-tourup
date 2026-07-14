<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE trips MODIFY COLUMN status ENUM('active', 'on_sale', 'completed', 'inactive', 'cancelled') NOT NULL DEFAULT 'on_sale'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE trips MODIFY COLUMN status ENUM('active', 'complete', 'on_sale') NOT NULL DEFAULT 'on_sale'");
    }
};
