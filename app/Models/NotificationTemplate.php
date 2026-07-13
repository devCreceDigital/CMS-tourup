<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'name', 'channel', 'subject', 'body', 'available_variables',
    ];

    protected $casts = [
        'available_variables' => 'json',
    ];

    public function rules(): HasMany
    {
        return $this->hasMany(NotificationRule::class);
    }
}
