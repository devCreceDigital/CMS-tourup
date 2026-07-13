<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingInstallment extends Model
{
    use HasFactory;

    protected $fillable = ['pricing_group_id', 'name', 'due_date', 'amount', 'order'];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function pricingGroup()
    {
        return $this->belongsTo(PricingGroup::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
