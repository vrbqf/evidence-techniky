<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ServiceRecordController;

Route::resource('equipments', EquipmentController::class);
Route::post('equipments/{equipment}/service-records', [ServiceRecordController::class, 'store'])->name('service-records.store');
Route::get('equipments/{equipment}/export-csv', [EquipmentController::class, 'exportCsv'])->name('equipments.export-csv');

// Přesměrování úvodní stránky na seznam zařízení
Route::get('/', function () {
    return redirect()->route('equipments.index');
});
