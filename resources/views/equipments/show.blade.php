<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Detail zařízení</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
<div class="max-w-4xl mx-auto px-4 py-8">

    <!-- Horní lišta / Navigace -->
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('equipments.index') }}" class="text-blue-600 hover:underline text-sm font-medium">&larr; Zpět na seznam</a>
        <div class="space-x-2">
            <a href="{{ route('equipments.export-csv', $equipment) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                Export do CSV
            </a>
            <a href="{{ route('equipments.edit', $equipment) }}" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                Upravit zařízení
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Karta zařízení -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        <h1 class="text-2xl font-bold mb-4">{{ $equipment->name }}</h1>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="block text-gray-500">Evidenční číslo</span>
                <span class="font-mono font-semibold">{{ $equipment->inventory_number }}</span>
            </div>
            <div>
                <span class="block text-gray-500">Stav</span>
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
                <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-semibold rounded-full border {{ $badges[$equipment->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $labels[$equipment->status] ?? $equipment->status }}
                    </span>
            </div>
            <div>
                <span class="block text-gray-500">Datum nákupu</span>
                <span class="font-medium">{{ $equipment->purchased_at->format('d. m. Y') }}</span>
            </div>
            <div>
                <span class="block text-gray-500">Celkové náklady na servis</span>
                <span class="font-bold text-lg text-gray-900">{{ number_format($equipment->total_service_cost, 2, ',', ' ') }} Kč</span>
            </div>
        </div>
    </div>

    <!-- Servisní historie a formulář pro přidání -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Seznam servisních zásahů (2 sloupce) -->
        <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-lg font-bold mb-4">Historie servisních zásahů</h2>

            <div class="space-y-4">
                @forelse($equipment->serviceRecords as $record)
                    <div class="border border-gray-100 bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-semibold text-gray-500">{{ $record->serviced_at->format('d. m. Y') }}</span>
                            <span class="font-bold text-blue-600 text-sm">{{ number_format($record->cost, 2, ',', ' ') }} Kč</span>
                        </div>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $record->description }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">To to zařízení nemá zatím žádné servisní záznamy.</p>
                @endforelse
            </div>
        </div>

        <!-- Formulář pro přidání nového servisu (1 sloupec) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
            <h2 class="text-lg font-bold mb-4">Přidat servisní zásah</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg mb-4 text-xs">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('service-records.store', $equipment) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Popis zásahu</label>
                    <textarea name="description" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Datum servisu</label>
                    <input type="date" name="serviced_at" value="{{ old('serviced_at', date('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cena (Kč)</label>
                    <input type="number" step="0.01" min="0" name="cost" value="{{ old('cost') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium shadow">
                    Přidat servis
                </button>
            </form>
        </div>

    </div>

</div>
</body>
</html>
