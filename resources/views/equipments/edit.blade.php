<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Upravit zařízení</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
<div class="max-w-xl mx-auto px-4 py-12">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h1 class="text-2xl font-bold mb-6">Upravit zařízení: {{ $equipment->name }}</h1>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('equipments.update', $equipment) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Název zařízení</label>
                <input type="text" name="name" value="{{ old('name', $equipment->name) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Evidenční číslo</label>
                <input type="text" name="inventory_number" value="{{ old('inventory_number', $equipment->inventory_number) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stav</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="active" {{ old('status', $equipment->status) === 'active' ? 'selected' : '' }}>Aktivní</option>
                    <option value="service" {{ old('status', $equipment->status) === 'service' ? 'selected' : '' }}>V servisu</option>
                    <option value="retired" {{ old('status', $equipment->status) === 'retired' ? 'selected' : '' }}>Vyřazeno</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Datum nákupu</label>
                <input type="date" name="purchased_at" value="{{ old('purchased_at', $equipment->purchased_at->format('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('equipments.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">Zpět</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow">Aktualizovat</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
