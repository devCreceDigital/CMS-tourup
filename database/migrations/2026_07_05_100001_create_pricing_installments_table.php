<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pricing_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_group_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('due_date');
            $table->decimal('amount', 10, 2);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricing_installments');
    }
};
