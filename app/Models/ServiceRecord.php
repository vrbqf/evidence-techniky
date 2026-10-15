<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRecord extends Model
{
    protected $table = 'service_records';

    protected $fillable = [
        'equipment_id',
        'description',
        'serviced_at',
        'cost',
    ];

    protected $casts = [
        'serviced_at' => 'date',
        'cost' => 'decimal:2',
    ];

    public function equipment(): BelongsTo
    {
        // Explicitně řekneme, že relace míří na model Equipment
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }
}
