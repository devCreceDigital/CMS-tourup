<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusSeat extends Model
{
    use HasFactory;

    protected $fillable = ['bus_id', 'seat_number', 'row', 'column', 'is_occupied'];

    protected $casts = ['is_occupied' => 'boolean'];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
