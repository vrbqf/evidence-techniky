<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Definice modelu ServiceRecord reprezentujícího jednotlivé servisní zásahy, dědí z Eloquent Model
class ServiceRecord extends Model
{
    // Explicitně určíme název tabulky v databázi, aby Laravel věděl, že má použít 'service_records'
    protected $table = 'service_records';

    // Seznam sloupců, do kterých je povoleno hromadně vkládat data (ochrana proti Mass Assignment)
    protected $fillable = [
        'equipment_id',   // ID zařízení, ke kterému servisní záznam patří (cizí klíč)
        'description',    // Popis provedeného servisu nebo závady
        'serviced_at',    // Datum, kdy byl servis realizován
        'cost',           // Cena/náklad na tento servisní zásah
    ];

    // Automatické přettypování datových typů při práci s databází
    protected $casts = [
        'serviced_at' => 'date',      // Převede sloupec na objekt data (Carbon)
        'cost' => 'decimal:2',        // Zajistí správné formátování ceny na 2 desetinná místa
    ];

    // Definice inverzní relace N:1 (každý servisní záznam patří právě jednomu konkrétnímu zařízení)
    public function equipment(): BelongsTo
    {
        // Explicitně řekneme, že relace míří na model Equipment a využívá sloupec 'equipment_id' jako cizí klíč
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }
}
