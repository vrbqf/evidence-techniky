{{-- Deklarace typu dokumentu HTML5 --}}
    <!DOCTYPE html>
{{-- Počáteční HTML tag s nastavením českého jazyka --}}
<html lang="cs">
{{-- Hlavička HTML dokumentu obsahující metadata --}}
<head>
    {{-- Nastavení kódování znaků na UTF-8 pro podporu diakritiky --}}
    <meta charset="UTF-8">
    {{-- Nastavení responsivního zobrazení pro mobilní zařízení a šířku obrazovky --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Titulek stránky zobrazený v záložce prohlížeče --}}
    <title>Evidence techniky</title>
    {{-- Načtení Tailwind CSS frameworku přes CDN pro stylování --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Ukončení hlavičky HTML dokumentu --}}
</head>
{{-- Tělo stránky s výchozími styly pro pozadí a barvu textu --}}
<body class="bg-gray-50 text-gray-900">
{{-- Hlavní obalový kontejner s omezenou maximální šířkou a vnitřním odsazením --}}
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Flexbox kontejner pro nadpis stránky a tlačítko přidání --}}
    <div class="flex justify-between items-center mb-6">
        {{-- Hlavní nadpis stránky --}}
        <h1 class="text-3xl font-bold">Evidence techniky</h1>
        {{-- Odkaz/tlačítko směřující na formulář pro vytvoření nového zařízení --}}
        <a href="{{ route('equipments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow">
            + Přidat zařízení
            {{-- Ukončení odkazu pro přidání zařízení --}}
        </a>
        {{-- Ukončení kontejneru hlavičky --}}
    </div>

    {{-- Podmínka ověřující existenci flash zprávy o úspěšné akci v session --}}
    @if(session('success'))
        {{-- Kontejner pro úspěšnou zprávu se zeleným pozadím a ohraničením --}}
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{-- Vypsání textu úspěšné zprávy ze session --}}
            {{ session('success') }}
            {{-- Ukončení kontejneru flash zprávy --}}
        </div>
        {{-- Ukončení podmínky @if --}}
    @endif

    {{-- Kontejner filtru stavů s bílým pozadím, stínem a zarovnáním prvků --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 flex gap-2 items-center">
        {{-- Textový popisek sekce filtrů --}}
        <span class="text-sm font-semibold text-gray-600 mr-2">Filtr stavu:</span>
        {{-- Odkaz pro zrušení filtru a zobrazení všech záznamů --}}
        <a href="{{ route('equipments.index') }}" class="px-3 py-1.5 text-sm rounded-lg {{ !request('status') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Všechna</a>
        {{-- Odkaz pro filtrování pouze aktivních zařízení --}}
        <a href="{{ route('equipments.index', ['status' => 'active']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'active' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">Aktivní</a>
        {{-- Odkaz pro filtrování zařízení nacházejících se v servisu --}}
        <a href="{{ route('equipments.index', ['status' => 'service']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'service' ? 'bg-yellow-500 text-white' : 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' }}">V servisu</a>
        {{-- Odkaz pro filtrování vyřazených zařízení --}}
        <a href="{{ route('equipments.index', ['status' => 'retired']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'retired' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">Vyřazeno</a>
        {{-- Ukončení kontejneru filtrů --}}
    </div>

    {{-- Obalový kontejner tabulky se zaoblenými rohy a skrytým přetečením --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Samotná HTML tabulka s plnou šířkou --}}
        <table class="w-full text-left border-collapse">
            {{-- Hlavička tabulky --}}
            <thead>
            {{-- Řádek hlavičky tabulky s šedým pozadím a malým písmem --}}
            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                {{-- Sloupec hlavičky pro název zařízení --}}
                <th class="p-4">Název</th>
                {{-- Sloupec hlavičky pro evidenční číslo --}}
                <th class="p-4">Evidenční číslo</th>
                {{-- Sloupec hlavičky pro stav zařízení --}}
                <th class="p-4">Stav</th>
                {{-- Sloupec hlavičky pro datum nákupu --}}
                <th class="p-4">Datum nákupu</th>
                {{-- Sloupec hlavičky pro celkové náklady na servis --}}
                <th class="p-4">Celkový servis</th>
                {{-- Sloupec hlavičky pro akční tlačítka zarovnaný vpravo --}}
                <th class="p-4 text-right">Akce</th>
                {{-- Ukončení řádku hlavičky --}}
            </tr>
            {{-- Ukončení hlavičky tabulky --}}
            </thead>
            {{-- Tělo tabulky s oddělovači řádků --}}
            <tbody class="divide-y divide-gray-200 text-sm">
            {{-- Cyklus procházející kolekci zařízení; pokud je prázdná, provede větev @empty --}}
            @forelse($equipments as $eq)
                {{-- Řádek tabulky pro konkrétní zařízení s efektu hover --}}
                <tr class="hover:bg-gray-50">
                    {{-- Buňka pro název zařízení se zvýrazněným písmem --}}
                    <td class="p-4 font-medium">
                        {{-- Odkaz směřující na detail daného zařízení --}}
                        <a href="{{ route('equipments.show', $eq) }}" class="text-blue-600 hover:underline">
                            {{-- Vypsání názvu zařízení --}}
                            {{ $eq->name }}
                            {{-- Ukončení odkazu na detail --}}
                        </a>
                        {{-- Ukončení buňky názvu --}}
                    </td>
                    {{-- Buňka pro evidenční číslo s monospaced písmem --}}
                    <td class="p-4 text-gray-600 font-mono">{{ $eq->inventory_number }}</td>
                    {{-- Buňka pro stav zařízení obsahující dynamický barevný štítek --}}
                    <td class="p-4">
                        {{-- PHP blok pro definici políček stylů a textových popisků stavů --}}
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
                        {{-- Inline HTML element (span) vykreslující barevný štítek stavu --}}
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $badges[$eq->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $labels[$eq->status] ?? $eq->status }}
                        </span>
                        {{-- Ukončení buňky stavu --}}
                    </td>
                    {{-- Buňka pro datum nákupu naformátované na český formát --}}
                    <td class="p-4 text-gray-600">{{ $eq->purchased_at->format('d. m. Y') }}</td>
                    {{-- Buňka pro celkovou cenu servisu se dvěma desetinnými místy --}}
                    <td class="p-4 font-semibold text-gray-800">{{ number_format($eq->total_service_cost, 2, ',', ' ') }} Kč</td>
                    {{-- Buňka pro akční tlačítka zarovnaná vpravo --}}
                    <td class="p-4 text-right space-x-2">
                        {{-- Odkaz na detail zařízení --}}
                        <a href="{{ route('equipments.show', $eq) }}" class="text-gray-600 hover:text-blue-600">Detail</a>
                        {{-- Odkaz na editační formulář zařízení --}}
                        <a href="{{ route('equipments.edit', $eq) }}" class="text-indigo-600 hover:underline">Upravit</a>
                        {{-- Formulář pro smazání záznamu s javascriptovým potvrzením --}}
                        <form action="{{ route('equipments.destroy', $eq) }}" method="POST" class="inline" onsubmit="return confirm('Opravdu smazat?')">
                            {{-- Laravel CSRF ochrana proti útokům --}}
                            @csrf
                            {{-- Laravel direktiva pro simulaci DELETE HTTP metody --}}
                            @method('DELETE')
                            {{-- Tlačítko pro odeslání formuláře ke smazání --}}
                            <button type="submit" class="text-red-600 hover:underline">Smazat</button>
                            {{-- Ukončení formuláře pro smazání --}}
                        </form>
                        {{-- Ukončení buňky akcí --}}
                    </td>
                    {{-- Ukončení řádku tabulky --}}
                </tr>
                {{-- Větev @empty spuštěná v případě, že kolekce zařízení je prázdná --}}
            @empty
                {{-- Řádek pro prázdný stav tabulky --}}
                <tr>
                    {{-- Buňka přes celou šířku tabulky s informací o nenalezení záznamů --}}
                    <td colspan="6" class="p-8 text-center text-gray-500">Žádná zařízení nebyla nalezena.</td>
                </tr>
                {{-- Ukončení cyklu @forelse --}}
            @endforelse
            {{-- Ukončení těla tabulky --}}
            </tbody>
            {{-- Ukončení tabulky --}}
        </table>
        {{-- Ukončení kontejneru tabulky --}}
    </div>

    {{-- Kontejner pro stránkování (pagination) zachovávající aktuální parametry URL --}}
    <div class="mt-4">
        {{ $equipments->withQueryString()->links() }}
        {{-- Ukončení kontejneru stránkování --}}
    </div>

    {{-- Ukončení hlavního obalového kontejneru --}}
</div>
{{-- Ukončení těla stránky --}}
</body>
{{-- Ukončení HTML dokumentu --}}
</html>
