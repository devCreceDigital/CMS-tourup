<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('dni')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('stage')->default('lead_nuevo');
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_contact_at')->nullable();
            $table->text('notes')->nullable();
            $table->integer('linked_travelers_count')->default(0);
            $table->integer('total_trips_count')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['dni', 'email']);
            $table->index('stage');
            $table->index('last_contact_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
