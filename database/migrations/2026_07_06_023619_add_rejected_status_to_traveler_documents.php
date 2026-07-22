<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE traveler_documents MODIFY COLUMN status ENUM('pending','in_review','complete','rejected') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down()
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE traveler_documents MODIFY COLUMN status ENUM('pending','in_review','complete') NOT NULL DEFAULT 'pending'");
        }
    }
};
