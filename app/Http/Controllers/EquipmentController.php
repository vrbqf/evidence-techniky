<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Http\Requests\EquipmentRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        if ($request->filled('status') && in_array($request->status, ['active', 'service', 'retired'])) {
            $query->where('status', $request->status);
        }

        $equipments = $query->withSum('serviceRecords', 'cost')->latest()->paginate(10);

        return view('equipments.index', compact('equipments'));
    }

    public function create()
    {
        return view('equipments.create');
    }

    public function store(EquipmentRequest $request)
    {
        Equipment::create($request->validated());

        return redirect()->route('equipments.index')->with('success', 'Zařízení bylo úspěšně vytvořeno.');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('serviceRecords');
        return view('equipments.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        return view('equipments.edit', compact('equipment'));
    }

    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        $equipment->update($request->validated());

        return redirect()->route('equipments.index')->with('success', 'Zařízení bylo aktualizováno.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('equipments.index')->with('success', 'Zařízení bylo smazáno.');
    }

    // Volitelné rozšíření: Export do CSV
    public function exportCsv(Equipment $equipment): StreamedResponse
    {
        $filename = "servisni-historie-{$equipment->inventory_number}.csv";

        return response()->streamDownload(function () use ($equipment) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($handle, ['ID', 'Popis zásahu', 'Datum servisu', 'Cena (CZK)'], ';');

            foreach ($equipment->serviceRecords as $record) {
                fputcsv($handle, [
                    $record->id,
                    $record->description,
                    $record->serviced_at->format('Y-m-d'),
                    $record->cost
                ], ';');
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
