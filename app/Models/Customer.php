<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    use SoftDeletes;

    const STAGES = [
        'lead_nuevo' => 'Lead nuevo',
        'cotizado' => 'Cotizado',
        'en_negociacion' => 'En negociación',
        'reserva_iniciada' => 'Reserva iniciada',
        'confirmado' => 'Confirmado/Cliente',
        'en_viaje' => 'En viaje',
        'postventa' => 'Postventa/Lealtad',
    ];

    protected $fillable = [
        'name', 'email', 'dni', 'phone', 'address',
        'stage', 'assigned_agent_id', 'last_contact_at',
        'notes', 'linked_travelers_count', 'total_trips_count', 'total_revenue',
    ];

    protected $casts = [
        'last_contact_at' => 'datetime',
        'total_revenue' => 'decimal:2',
    ];

    public function interactions(): HasMany
    {
        return $this->hasMany(CustomerInteraction::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function travelers(): BelongsToMany
    {
        return $this->belongsToMany(Traveler::class, 'customer_traveler')
            ->withTimestamps();
    }
}
