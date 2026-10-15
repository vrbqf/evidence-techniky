<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'inventory_number',
        'status',
        'purchased_at',
    ];

    protected $casts = [
        'purchased_at' => 'date',
    ];

    public function serviceRecords(): HasMany
    {
        return $this->hasMany(ServiceRecord::class);
    }

    // Součet servisních nákladů
    public function getTotalServiceCostAttribute(): float
    {
        return $this->serviceRecords()->sum('cost');
    }
}
