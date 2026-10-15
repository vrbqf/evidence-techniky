<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evidence techniky</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
<div class="max-w-6xl mx-auto px-4 py-8">

    <!-- Hlavička -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Evidence techniky</h1>
        <a href="{{ route('equipments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow">
            + Přidat zařízení
        </a>
    </div>

    <!-- Úspěšná hláška -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtry podle stavu -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 flex gap-2 items-center">
        <span class="text-sm font-semibold text-gray-600 mr-2">Filtr stavu:</span>
        <a href="{{ route('equipments.index') }}" class="px-3 py-1.5 text-sm rounded-lg {{ !request('status') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Všechna</a>
        <a href="{{ route('equipments.index', ['status' => 'active']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'active' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">Aktivní</a>
        <a href="{{ route('equipments.index', ['status' => 'service']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'service' ? 'bg-yellow-500 text-white' : 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' }}">V servisu</a>
        <a href="{{ route('equipments.index', ['status' => 'retired']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'retired' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">Vyřazeno</a>
    </div>

    <!-- Tabulka -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <th class="p-4">Název</th>
                <th class="p-4">Evidenční číslo</th>
                <th class="p-4">Stav</th>
                <th class="p-4">Datum nákupu</th>
                <th class="p-4">Celkový servis</th>
                <th class="p-4 text-right">Akce</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
            @forelse($equipments as $eq)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-medium">
                        <a href="{{ route('equipments.show', $eq) }}" class="text-blue-600 hover:underline">
                            {{ $eq->name }}
                        </a>
                    </td>
                    <td class="p-4 text-gray-600 font-mono">{{ $eq->inventory_number }}</td>
                    <td class="p-4">
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
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $badges[$eq->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $labels[$eq->status] ?? $eq->status }}
                                </span>
                    </td>
                    <td class="p-4 text-gray-600">{{ $eq->purchased_at->format('d. m. Y') }}</td>
                    <td class="p-4 font-semibold text-gray-800">{{ number_format($eq->total_service_cost, 2, ',', ' ') }} Kč</td>
                    <td class="p-4 text-right space-x-2">
                        <a href="{{ route('equipments.show', $eq) }}" class="text-gray-600 hover:text-blue-600">Detail</a>
                        <a href="{{ route('equipments.edit', $eq) }}" class="text-indigo-600 hover:underline">Upravit</a>
                        <form action="{{ route('equipments.destroy', $eq) }}" method="POST" class="inline" onsubmit="return confirm('Opravdu smazat?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Smazat</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500">Žádná zařízení nebyla nalezena.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Stránkování -->
    <div class="mt-4">
        {{ $equipments->withQueryString()->links() }}
    </div>

</div>
</body>
</html>
