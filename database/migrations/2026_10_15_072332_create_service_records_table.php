<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Vytvoří anonymní třídu dědící od třídy Migration pro správu schématu databáze
return new class extends Migration
{
    /**
     * Run the migrations.
     * Metoda up() se provede při spuštění migrace a vytvoří tabulku pro servisní záznamy.
     */
    public function up(): void
    {
        // Vytvoří novou databázovou tabulku s názvem 'service_records'
        Schema::create('service_records', function (Blueprint $table) {

            // Vytvoří primární klíč 'id' (auto-incrementing integer)
            $table->id();

            // Vytvoří cizí klíč 'equipment_id' odkazující na tabulku 'equipments' a nastaví kaskádové mazání (pokud se smaže zařízení, smažou se i jeho servisy)
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();

            // Vytvoří sloupec 'description' typu TEXT pro podrobný popis provedeného servisního zásahu
            $table->text('description');

            // Vytvoří sloupec 'serviced_at' pro uložení data, kdy byl servis realizován
            $table->date('serviced_at');

            // Vytvoří sloupec 'cost' pro cenu servisu (10 celkových míst, z toho 2 desetinná místa)
            $table->decimal('cost', 10, 2);

            // Automaticky vytvoří sloupce 'created_at' a 'updated_at' pro sledování historie záznamu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Metoda down() slouží k odvolání migrace (vrácení změn zpět).
     */
    public function down(): void
    {
        // Bezpečně odstraní tabulku 'service_records', pokud v databázi existuje
        Schema::dropIfExists('service_records');
    }
};
