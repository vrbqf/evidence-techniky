<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Http\Requests\ServiceRecordRequest;

class ServiceRecordController extends Controller
{
    public function store(ServiceRecordRequest $request, Equipment $equipment)
    {
        $equipment->serviceRecords()->create($request->validated());

        return redirect()->route('equipments.show', $equipment)->with('success', 'Servisní záznam byl přidán.');
    }
}
