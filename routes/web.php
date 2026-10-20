<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ServiceRecordController;

// Automatické vygenerování kompletní sady RESTful rout pro správu zařízení (index, create, store, show, edit, update, destroy)
Route::resource('equipments', EquipmentController::class);

// Definice POST routy pro uložení nového servisního záznamu k danému zařízení (využívá Route Model Binding)
Route::post('equipments/{equipment}/service-records', [ServiceRecordController::class, 'store'])->name('service-records.store');

// Definice GET routy pro stažení CSV exportu servisní historie konkrétního zařízení
Route::get('equipments/{equipment}/export-csv', [EquipmentController::class, 'exportCsv'])->name('equipments.export-csv');

// Přesměrování výchozí kořenové URL adresy (domovské stránky) přímo na hlavní přehled zařízení
Route::get('/', function () {
    return redirect()->route('equipments.index');
});
