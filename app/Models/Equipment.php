<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Definice modelu Equipment reprezentujícího tabulku se zařízeními, dědí z Eloquent Model
class Equipment extends Model
{
    // Explicitně určíme název databázové tabulky, aby Laravel věděl, že má použít 'equipments'
    protected $table = 'equipments';

    // Seznam atributů (sloupců), do kterých je povoleno hromadné vkládání dat (Mass Assignment Protection)
    protected $fillable = [
        'name',               // Název zařízení
        'inventory_number',   // Evidenční číslo
        'status',             // Stav zařízení (aktivní, v servisu, vyřazené)
        'purchased_at',       // Datum nákupu
    ];

    // Automatické přettypování datových typů při načtení z databáze nebo uložení
    protected $casts = [
        'purchased_at' => 'date', // Převede sloupec 'purchased_at' na objekt typu Carbon (datum)
    ];

    // Definice datové relace 1:N (jedno zařízení má, nebo může mít více servisních záznamů)
    public function serviceRecords(): HasMany
    {
        return $this->hasMany(ServiceRecord::class);
    }

    // Vlastní Accessor (vypočítaný atribut) pro celkové náklady na servis daného zařízení
    public function getTotalServiceCostAttribute(): float
    {
        // Projde všechny servisní záznamy tohoto zařízení a sečte hodnoty ve sloupci 'cost'
        return $this->serviceRecords()->sum('cost');
    }
}
