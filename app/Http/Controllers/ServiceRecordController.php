<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Http\Requests\ServiceRecordRequest;

// Kontrolér pro správu servisních záznamů, dědí ze základního Controlleru v Laravelu
class ServiceRecordController extends Controller
{
    // Uložení nového servisního záznamu k danému zařízení (využívá Route Model Binding pro automatické načtení $equipment)
    public function store(ServiceRecordRequest $request, Equipment $equipment)
    {
        // Vytvoří nový servisní záznam přímo přes navázanou relaci serviceRecords()
        // Laravel díky tomu automaticky doplní cizí klíč 'equipment_id' podle načteného $equipment
        $equipment->serviceRecords()->create($request->validated());

        // Přesměruje uživatele zpět na detail konkrétního zařízení a předá úspěšnou flash zprávu
        return redirect()->route('equipments.show', $equipment)->with('success', 'Servisní záznam byl přičten.');
    }
}
