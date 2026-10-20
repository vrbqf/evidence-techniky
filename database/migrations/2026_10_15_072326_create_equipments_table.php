<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Vytvoří anonymní třídu dědící od třídy Migration, což je standardní způsob zápisu migrací v Laravelu
return new class extends Migration
{
    /**
     * Run the migrations.
     * Metoda up() se spustí při provedení migrace (příkaz 'php artisan migrate') a vytvoří databázovou strukturu.
     */
    public function up(): void
    {
        // Vytvoří novou tabulku s názvem 'equipments' v databázi
        Schema::create('equipments', function (Blueprint $table) {

            // Vytvoří primární klíč 'id' (auto-incrementing integer, výchozí identifikátor záznamu)
            $table->id();

            // Vytvoří sloupec 'name' typu VARCHAR pro uložení názvu technického vybavení
            $table->string('name');

            // Vytvoří sloupec 'inventory_number' pro evidenční číslo a zároveň zajistí, že bude jedinečné (unique)
            $table->string('inventory_number')->unique();

            // Vytvoří výčtový typ (enum) 'status' s povolenými stavy a nastaví výchozí hodnotu na 'active' (aktivní)
            $table->enum('status', ['active', 'service', 'retired'])->default('active');

            // Vytvoří sloupec 'purchased_at' pro uložení data nákupu/pořízení zařízení
            $table->date('purchased_at');

            // Automaticky vytvoří sloupce 'created_at' a 'updated_at' pro sledování času vytvoření a poslední úpravy záznamu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Metoda down() slouží k vrácení změn zpět (příkaz 'php artisan migrate:rollback') – v tomto případě tabulku smaže.
     */
    public function down(): void
    {
        // Bezpečně odstraní tabulku 'equipments', pokud v databázi existuje
        Schema::dropIfExists('equipments');
    }
};
