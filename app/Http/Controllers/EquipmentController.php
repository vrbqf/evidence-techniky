<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Http\Requests\EquipmentRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

// Hlavní kontrolér pro správu zařízení (EquipmentController), dědí ze základního Controlleru v Laravelu
class EquipmentController extends Controller
{
    // Zobrazení seznamu zařízení s možností filtrování podle stavu, výpočtu nákladů a stránkováním
    public function index(Request $request)
    {
        // Inicializace Eloquent dotazu pro model Equipment
        $query = Equipment::query();

        // Pokud uživatel odeslal filtr stavu a ten je platný (aktivní, v servisu, vyřazený), aplikuje ho na dotaz
        if ($request->filled('status') && in_array($request->status, ['active', 'service', 'retired'])) {
            $query->where('status', $request->status);
        }

        // Načte zařízení, předem k nim přičte celkové náklady na servis (withSum), seřadí od nejnovějších a rozdělí po 10 položkách na stránku
        $equipments = $query->withSum('serviceRecords', 'cost')->latest()->paginate(10);

        // Vrátí Blade šablonu pro přehled zařízení a předá jí získaná data
        return view('equipments.index', compact('equipments'));
    }

    // Zobrazení formuláře pro vytvoření nového zařízení
    public function create()
    {
        return view('equipments.create');
    }

    // Zpracování odeslaného formuláře a uložení nového zařízení do databáze
    public function store(EquipmentRequest $request)
    {
        // Vytvoří nový záznam v databázi pomocí dat, která prošla validací v EquipmentRequest
        Equipment::create($request->validated());

        // Přesměruje zpět na hlavní výpis s flash zprávou o úspěšném vytvoření
        return redirect()->route('equipments.index')->with('success', 'Zařízení bylo úspěšně vytvořeno.');
    }

    // Zobrazení detailu konkrétního zařízení včetně načtení jeho servisní historie
    public function show(Equipment $equipment)
    {
        // Načte relaci se servisními záznamy (eager loading) pro dané zařízení
        $equipment->load('serviceRecords');

        // Vrátí detailní šablonu a předá jí data
        return view('equipments.show', compact('equipment'));
    }

    // Zobrazení formuláře pro úpravu (editaci) existujícího zařízení
    public function edit(Equipment $equipment)
    {
        return view('equipments.edit', compact('equipment'));
    }

    // Aktualizace existujícího zařízení v databázi na základě validovaných dat
    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        // Aktualizuje záznam v databázi daty z formuláře
        $equipment->update($request->validated());

        // Přesměruje zpět na přehled s potvrzující zprávou
        return redirect()->route('equipments.index')->with('success', 'Zařízení bylo aktualizováno.');
    }

    // Úplné odstranění zařízení z databáze
    public function destroy(Equipment $equipment)
    {
        // Smaže záznam zařízení (díky nastavenému kaskádovému mazání v migraci se automaticky smažou i jeho servisní záznamy)
        $equipment->delete();

        // Přesměruje na výpis s informací o smazání
        return redirect()->route('equipments.index')->with('success', 'Zařízení bylo smazáno.');
    }

    // Volitelné rozšíření: Export servisní historie konkrétního zařízení do CSV souboru
    public function exportCsv(Equipment $equipment): StreamedResponse
    {
        // Vytvoří dynamický názvu souboru obsahující evidenční číslo zařízení
        $filename = "servisni-historie-{$equipment->inventory_number}.csv";

        // Vrátí proudovou odpověď (streamed response) pro efektivní stahování velkých souborů bez zatížení paměti
        return response()->streamDownload(function () use ($equipment) {
            // Otevře výstupní proud pro zápis dat
            $handle = fopen('php://output', 'w');

            // Zapíše UTF-8 BOM (Byte Order Mark), aby Excel správně zobrazil českou diakritiku
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Zapíše hlavičku CSV tabulky oddělenou středníkem
            fputcsv($handle, ['ID', 'Popis zásahu', 'Datum servisu', 'Cena (CZK)'], ';');

            // Projde všechny servisní záznamy daného zařízení a zapíše je jako řádky do CSV
            foreach ($equipment->serviceRecords as $record) {
                fputcsv($handle, [
                    $record->id,
                    $record->description,
                    $record->serviced_at->format('Y-m-d'),
                    $record->cost
                ], ';');
            }

            // Uzavře souborový proud
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
