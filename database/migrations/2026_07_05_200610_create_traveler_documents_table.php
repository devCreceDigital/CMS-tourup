<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traveler_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('traveler_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // authorization, insurance, medical, id_card, other
            $table->string('label'); // Autorización padres, Seguro, Ficha médica, DNI, etc.
            $table->string('file_path')->nullable();
            $table->enum('status', ['pending', 'in_review', 'complete'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['trip_id', 'traveler_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traveler_documents');
    }
};
