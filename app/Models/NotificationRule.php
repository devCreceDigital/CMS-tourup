<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationRule extends Model
{
    protected $fillable = [
        'name', 'event', 'channel', 'template_id', 'conditions', 'active',
    ];

    protected $casts = [
        'conditions' => 'json',
        'active' => 'boolean',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
