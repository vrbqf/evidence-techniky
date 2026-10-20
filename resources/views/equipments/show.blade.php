{{-- Deklarace typu dokumentu jako HTML5 --}}
    <!DOCTYPE html>
{{-- Počáteční HTML element s nastavením českého jazyka --}}
<html lang="cs">
{{-- Hlavička HTML dokumentu --}}
<head>
    {{-- Nastavení kódování znaků na UTF-8 pro správnou podporu diakritiky --}}
    <meta charset="UTF-8">
    {{-- Titulek stránky v záložce prohlížeče --}}
    <title>Detail zařízení</title>
    {{-- Načtení Tailwind CSS frameworku přes CDN pro stylování rozvržení --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Ukončení hlavičky dokumentu --}}
</head>
{{-- Tělo stránky s šedým pozadím a výchozí barvou textu --}}
<body class="bg-gray-50 text-gray-900">
{{-- Hlavní obalový kontejner s omezenou šířkou, vycentrovaný na středu --}}
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Horní navigační lišta pro návrat a akční tlačítka --}}
    <div class="flex justify-between items-center mb-6">
        {{-- Odkaz pro návrat zpět na hlavní seznam zařízení --}}
        <a href="{{ route('equipments.index') }}" class="text-blue-600 hover:underline text-sm font-medium">&larr; Zpět na seznam</a>
        {{-- Kontejner pro tlačítka akcí (export do CSV a úprava zařízení) --}}
        <div class="space-x-2">
            {{-- Odkaz/tlačítko pro spuštění exportu servisní historie daného zařízení do CSV --}}
            <a href="{{ route('equipments.export-csv', $equipment) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                Export do CSV
                {{-- Ukončení odkazu pro CSV export --}}
            </a>
            {{-- Odkaz směřující na editační formulář tohoto zařízení --}}
            <a href="{{ route('equipments.edit', $equipment) }}" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                Upravit zařízení
                {{-- Ukončení odkazu pro úpravu --}}
            </a>
            {{-- Ukončení kontejneru tlačítek --}}
        </div>
        {{-- Ukončení horní navigační lišty --}}
    </div>

    {{-- Podmínka ověřující existenci flash zprávy v session (např. po přidání servisu) --}}
    @if(session('success'))
        {{-- Kontejner pro úspěšnou zprávu se zeleným pozadím --}}
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{-- Vypsání textu úspěšné zprávy --}}
            {{ session('success') }}
            {{-- Ukončení kontejneru zprávy --}}
        </div>
        {{-- Ukončení podmínky @if --}}
    @endif

    {{-- Karta s hlavními informacemi o zařízení --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        {{-- Hlavní nadpis obsahující název konkrétního zařízení --}}
        <h1 class="text-2xl font-bold mb-4">{{ $equipment->name }}</h1>
        {{-- Mřížka (grid) pro přehledné rozvržení detailů zařízení ve sloupcích --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            {{-- Blok pro evidenční číslo --}}
            <div>
                {{-- Popisek položky --}}
                <span class="block text-gray-500">Evidenční číslo</span>
                {{-- Hodnota evidenčního čísla monospaced písmem --}}
                <span class="font-mono font-semibold">{{ $equipment->inventory_number }}</span>
            </div>
            {{-- Blok pro stav zařízení --}}
            <div>
                {{-- Popisek stavu --}}
                <span class="block text-gray-500">Stav</span>
                {{-- PHP blok pro definici stylů a českých popisků stavů --}}
                @php
                    $badges = [
                        'active' => 'bg-green-100 text-green-800 border-green-300',
                        'service' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'retired' => 'bg-red-100 text-red-800 border-red-300',
                    ];
                    $labels = [
                        'active' => 'Aktivní',
                        'service' => 'V servisu',
                        'retired' => 'Vyřazeno',
                    ];
                @endphp
                {{-- Vizuální štítek (badge) vybarvený podle aktuálního stavu --}}
                <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-semibold rounded-full border {{ $badges[$equipment->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $labels[$equipment->status] ?? $equipment->status }}
                </span>
            </div>
            {{-- Blok pro datum nákupu --}}
            <div>
                {{-- Popisek data nákupu --}}
                <span class="block text-gray-500">Datum nákupu</span>
                {{-- Naformátované datum nákupu --}}
                <span class="font-medium">{{ $equipment->purchased_at->format('d. m. Y') }}</span>
            </div>
            {{-- Blok pro celkové náklady na servis --}}
            <div>
                {{-- Popisek nákladů --}}
                <span class="block text-gray-500">Celkové náklady na servis</span>
                {{-- Vypsání celkové sumy za servis naformátované na měnu --}}
                <span class="font-bold text-lg text-gray-900">{{ number_format($equipment->total_service_cost, 2, ',', ' ') }} Kč</span>
            </div>
            {{-- Ukončení mřížky detailů --}}
        </div>
        {{-- Ukončení karty zařízení --}}
    </div>

    {{-- Dvousloupcové rozvržení pro historii servisních zásahů a formulář pro přidání nového servisu --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Levá větší sekce (zabírající 2 sloupce): Seznam servisních zásahů --}}
        <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            {{-- Nadpis sekce historie --}}
            <h2 class="text-lg font-bold mb-4">Historie servisních zásahů</h2>

            {{-- Obalový kontejner pro jednotlivé servisní záznamy --}}
            <div class="space-y-4">
                {{-- Cyklus procházející servisní záznamy zařízení; pokud žádné nejsou, spustí se větev @empty --}}
                @forelse($equipment->serviceRecords as $record)
                    {{-- Karta konkrétního servisního záznamu --}}
                    <div class="border border-gray-100 bg-gray-50 p-4 rounded-lg">
                        {{-- Horní řádek karty servisu (datum a cena) --}}
                        <div class="flex justify-between items-start mb-2">
                            {{-- Datum provedení servisu --}}
                            <span class="text-xs font-semibold text-gray-500">{{ $record->serviced_at->format('d. m. Y') }}</span>
                            {{-- Cena konkrétního servisního zásahu --}}
                            <span class="font-bold text-blue-600 text-sm">{{ number_format($record->cost, 2, ',', ' ') }} Kč</span>
                        </div>
                        {{-- Textový popis servisního zásahu se zachováním konců řádků --}}
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $record->description }}</p>
                    </div>
                    {{-- Větev spuštěná v případě, že zařízení nemá žádné servisní záznamy --}}
                @empty
                    {{-- Informační text o absenci servisních záznamů --}}
                    <p class="text-sm text-gray-500">To to zařízení nemá zatím žádné servisní záznamy.</p>
                    {{-- Ukončení cyklu @forelse --}}
                @endforelse
            </div>
        </div>

        {{-- Pravá menší sekce (1 sloupec): Formulář pro přidání nového servisního zásahu --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
            {{-- Nadpis formuláře --}}
            <h2 class="text-lg font-bold mb-4">Přidat servisní zásah</h2>

            {{-- Podmínka pro zobrazení validačních chyb formuláře servisu --}}
            @if ($errors->any())
                {{-- Kontejner chyb --}}
                <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg mb-4 text-xs">
                    {{-- Seznam chyb --}}
                    <ul class="list-disc pl-4 space-y-1">
                        {{-- Cyklus pro výpis chyb --}}
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Odesílací formulář pro uložení nového servisu k danému zařízení --}}
            <form action="{{ route('service-records.store', $equipment) }}" method="POST" class="space-y-4">
                {{-- CSRF ochrana --}}
                @csrf

                {{-- Skupina pro popis zásahu --}}
                <div>
                    {{-- Popisek textového pole --}}
                    <label class="block text-xs font-medium text-gray-700 mb-1">Popis zásahu</label>
                    {{-- Textarea pro zadání popisu --}}
                    <textarea name="description" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description') }}</textarea>
                </div>

                {{-- Skupina pro datum servisu --}}
                <div>
                    {{-- Popisek pro datum --}}
                    <label class="block text-xs font-medium text-gray-700 mb-1">Datum servisu</label>
                    {{-- Input pro datum předvyplněný aktuálním dnem --}}
                    <input type="date" name="serviced_at" value="{{ old('serviced_at', date('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- Skupina pro cenu servisu --}}
                <div>
                    {{-- Popisek ceny --}}
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cena (Kč)</label>
                    {{-- Číselné input pole s krokem na desetinná místa a minimální hodnotou 0 (ochrana proti záporným číslům) --}}
                    <input type="number" step="0.01" min="0" name="cost" value="{{ old('cost') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- Tlačítko pro odeslání a přidání servisu --}}
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium shadow">
                    Přidat servis
                </button>
                {{-- Ukončení formuláře --}}
            </form>
        </div>

    </div>

    {{-- Ukončení hlavního obalového kontejneru --}}
</div>
{{-- Ukončení těla stránky --}}
</body>
{{-- Ukončení HTML dokumentu --}}
</html>
