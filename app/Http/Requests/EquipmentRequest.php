<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $equipmentId = $this->route('equipment')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'inventory_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('equipments', 'inventory_number')->ignore($equipmentId),
            ],
            'status' => ['required', Rule::in(['active', 'service', 'retired'])],
            'purchased_at' => ['required', 'date'],
        ];
    }
}
