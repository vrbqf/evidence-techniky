<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// Třída pro validaci vstupních dat při vytváření nebo úpravě zařízení, dědí z Laravel FormRequest
class EquipmentRequest extends FormRequest
{
    // Metoda určuje, zda má uživatel oprávnění tento request provést (zde povoleno všem -> true)
    public function authorize(): bool
    {
        return true;
    }

    // Definice validačních pravidel pro jednotlivá pole ve formuláři
    public function rules(): array
    {
        // Získá ID aktuálně upravovaného zařízení z URL (pokud jde o editaci, jinak je null)
        $equipmentId = $this->route('equipment')?->id;

        return [
            // Název zařízení je povinný, musí být textový řetězec a může mít maximálně 255 znaků
            'name' => ['required', 'string', 'max:255'],

            // Evidenční číslo je povinné, textové, max 255 znaků a v tabulce 'equipments' musí být unikátní
            'inventory_number' => [
                'required',
                'string',
                'max:255',
                // Při úpravě (editaci) ignoruje evidenční číslo aktuálního zařízení, aby hlášení nekřičelo duplicitou samo na sebe
                Rule::unique('equipments', 'inventory_number')->ignore($equipmentId),
            ],

            // Stav zařízení je povinný a musí se shodovat s jednou z povolených hodnot v poli
            'status' => ['required', Rule::in(['active', 'service', 'retired'])],

            // Datum nákupu je povinné a musí mít platný formát data
            'purchased_at' => ['required', 'date'],
        ];
    }
}
