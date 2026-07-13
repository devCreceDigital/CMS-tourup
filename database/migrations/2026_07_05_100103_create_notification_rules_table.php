<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('event'); // nueva_reserva, pago_vencido, doc_faltante, pre_viaje, post_viaje, lead_sin_respuesta
            $table->string('channel'); // email, whatsapp, ambos
            $table->foreignId('template_id')->nullable()->constrained('notification_templates')->nullOnDelete();
            $table->json('conditions')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('event');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_rules');
    }
};
