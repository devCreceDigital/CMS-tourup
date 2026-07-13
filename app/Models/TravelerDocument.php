<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id', 'traveler_id', 'type', 'label',
        'file_path', 'status', 'notes',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function traveler()
    {
        return $this->belongsTo(Traveler::class);
    }
}
