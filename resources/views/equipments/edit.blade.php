{{-- Deklarace typu dokumentu jako HTML5 --}}
    <!DOCTYPE html>
{{-- Počáteční HTML element s nastavením českého jazyka --}}
<html lang="cs">
{{-- Hlavička HTML dokumentu --}}
<head>
    {{-- Nastavení kódování znaků na UTF-8 pro správnou podporu diakritiky --}}
    <meta charset="UTF-8">
    {{-- Titulek stránky v záložce prohlížeče --}}
    <title>Upravit zařízení</title>
    {{-- Načtení Tailwind CSS frameworku přes CDN pro stylování formuláře --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Ukončení hlavičky dokumentu --}}
</head>
{{-- Tělo stránky s šedým pozadím a základní barvou textu --}}
<body class="bg-gray-50 text-gray-900">
{{-- Centrovaný obalový kontejner s omezenou maximální šířkou a vnitřním odsazením --}}
<div class="max-w-xl mx-auto px-4 py-12">
    {{-- Karta formuláře s bílým pozadím, zaoblenými rohy, stínem a ohraničením --}}
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        {{-- Hlavní nadpis formuláře obsahující dynamicky vypsaný název upravovaného zařízení --}}
        <h1 class="text-2xl font-bold mb-6">Upravit zařízení: {{ $equipment->name }}</h1>

        {{-- Podmínka ověřující, zda při odeslání nastaly nějaké validační chyby --}}
        @if ($errors->any())
            {{-- Kontejner pro chybové hlášky s jemným červeným pozadím a ohraničením --}}
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 text-sm">
                {{-- Seznam chybových hlášení s odrážkami --}}
                <ul class="list-disc pl-5 space-y-1">
                    {{-- Cyklus procházející všechny dostupné validační chyby --}}
                    @foreach ($errors->all() as $error)
                        {{-- Výpis konkrétní chybové hlášky --}}
                        <li>{{ $error }}</li>
                        {{-- Ukončení cyklu @foreach --}}
                    @endforeach
                    {{-- Ukončení seznamu chyb --}}
                </ul>
                {{-- Ukončení chybového kontejneru --}}
            </div>
            {{-- Ukončení podmínky @if --}}
        @endif

        {{-- Odesílací formulář směřující na metodu update s předáním konkrétního modelu zařízení --}}
        <form action="{{ route('equipments.update', $equipment) }}" method="POST" class="space-y-4">
            {{-- Laravel direktiva pro ochranu před CSRF útoky --}}
            @csrf
            {{-- Laravel direktiva pro simulaci PUT HTTP metody (nutná pro aktualizaci záznamů) --}}
            @method('PUT')

            {{-- Skupina pro pole názvu zařízení --}}
            <div>
                {{-- Popisek vstupního pole --}}
                <label class="block text-sm font-medium text-gray-700 mb-1">Název zařízení</label>
                {{-- Textové input pole; preferuje starou hodnotu po chybě, jinak načte aktuální hodnotu z databáze --}}
                <input type="text" name="name" value="{{ old('name', $equipment->name) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            {{-- Skupina pro pole evidenčního čísla --}}
            <div>
                {{-- Popisek vstupního pole --}}
                <label class="block text-sm font-medium text-gray-700 mb-1">Evidenční číslo</label>
                {{-- Input pole pro evidenční číslo s předvyplněnou hodnotou --}}
                <input type="text" name="inventory_number" value="{{ old('inventory_number', $equipment->inventory_number) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            {{-- Skupina pro výběr stavu zařízení --}}
            <div>
                {{-- Popisek rozevíracího seznamu --}}
                <label class="block text-sm font-medium text-gray-700 mb-1">Stav</label>
                {{-- Select element pro výběr aktuálního stavu --}}
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    {{-- Možnost 'Aktivní' s kontrolou, zda je aktuálně vybraná --}}
                    <option value="active" {{ old('status', $equipment->status) === 'active' ? 'selected' : '' }}>Aktivní</option>
                    {{-- Možnost 'V servisu' s kontrolou vybraného stavu --}}
                    <option value="service" {{ old('status', $equipment->status) === 'service' ? 'selected' : '' }}>V servisu</option>
                    {{-- Možnost 'Vyřazeno' s kontrolou vybraného stavu --}}
                    <option value="retired" {{ old('status', $equipment->status) === 'retired' ? 'selected' : '' }}>Vyřazeno</option>
                    {{-- Ukončení select elementu --}}
                </select>
            </div>

            {{-- Skupina pro datum nákupu --}}
            <div>
                {{-- Popisek pro datumové pole --}}
                <label class="block text-sm font-medium text-gray-700 mb-1">Datum nákupu</label>
                {{-- Vstupní pole typu date s naformátovaným datem z databáze (YYYY-MM-DD) --}}
                <input type="date" name="purchased_at" value="{{ old('purchased_at', $equipment->purchased_at->format('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            {{-- Kontejner pro tlačítka akcí zarovnaná vpravo --}}
            <div class="flex justify-end space-x-3 pt-4">
                {{-- Odkaz pro návrat zpět na hlavní výpis --}}
                <a href="{{ route('equipments.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">Zpět</a>
                {{-- Tlačítko pro odeslání formuláře a aktualizaci záznamu --}}
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow">Aktualizovat</button>
                {{-- Ukončení kontejneru tlačítek --}}
            </div>
            {{-- Ukončení formuláře --}}
        </form>
        {{-- Ukončení karty formuláře --}}
    </div>
    {{-- Ukončení obalového kontejneru --}}
</div>
{{-- Ukončení těla stránky --}}
</body>
{{-- Ukončení HTML dokumentu --}}
</html>
