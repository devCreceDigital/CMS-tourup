<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppSettings extends Model
{
    protected $fillable = [
        'account_id', 'phone_number_id', 'token', 'webhook_verify_token', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
