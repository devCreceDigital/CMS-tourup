<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'admin_name', 'email', 'phone', 'welcome_phrase',
        'about', 'logo', 'favicon', 'address', 'ruc',
        'active_theme', 'is_installed',
    ];

    protected $casts = [
        'is_installed' => 'boolean',
    ];
}
