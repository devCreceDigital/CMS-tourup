<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingGroup extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function installments()
    {
        return $this->hasMany(PricingInstallment::class)->orderBy('order');
    }
}
