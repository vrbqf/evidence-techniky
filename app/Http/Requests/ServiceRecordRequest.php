<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

// Třída pro validaci vstupních dat při vytváření servisního záznamu, dědí z Laravel FormRequest
class ServiceRecordRequest extends FormRequest
{
    // Metoda určuje, zda má uživatel oprávnění tento request provést (zde povoleno všem -> true)
    public function authorize(): bool
    {
        return true;
    }

    // Definice validačních pravidel pro jednotlivá pole formuláře
    public function rules(): array
    {
        return [
            // Popis servisu je povinný a musí být textového typu
            'description' => ['required', 'string'],

            // Datum provedení servisu je povinné a musí mít platný formát data
            'serviced_at' => ['required', 'date'],

            // Cena servisu je povinná, musí být číselná (numeric) a nesmí být záporná (min:0)
            'cost' => ['required', 'numeric', 'min:0'],
        ];
    }
}
