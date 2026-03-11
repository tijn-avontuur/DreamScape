<x-layouts::app :title="__('Beheer — Voorwerpen Beheren')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">Voorwerpen Beheren</h1>
            <a href="{{ route('admin.items.create') }}"
               class="rounded-lg bg-purple-700 px-4 py-2 text-sm font-medium text-white hover:bg-purple-600 transition">
                + Nieuw voorwerp
            </a>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="rounded-lg border border-green-700/40 bg-green-900/20 px-4 py-3 text-green-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Item table --}}
        <div class="overflow-x-auto rounded-xl border border-purple-900/40 bg-[#1a1035]">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-purple-900/40 text-left text-xs uppercase tracking-widest text-purple-500">
                        <th class="px-4 py-3">Naam</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Zeldzaamheid</th>
                        <th class="px-4 py-3 text-center">Kracht</th>
                        <th class="px-4 py-3 text-center">Snelheid</th>
                        <th class="px-4 py-3 text-center">Duurzaamheid</th>
                        <th class="px-4 py-3 text-center">Bezitters</th>
                        <th class="px-4 py-3 text-center">Acties</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-900/30">
                    @php
                        $typeLabels = ['weapon' => 'Wapen', 'armor' => 'Uitrusting', 'accessory' => 'Accessoire', 'consumable' => 'Verbruiksartikel', 'other' => 'Overig'];
                    @endphp
                    @forelse($items as $item)
                        <tr class="hover:bg-purple-900/10 transition">
                            <td class="px-4 py-3 font-medium text-white">{{ $item->name }}</td>
                            <td class="px-4 py-3 text-purple-400">{{ $typeLabels[$item->type] ?? $item->type }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $item->rarityBadgeClass() }}">
                                    {{ ucfirst($item->rarity) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-red-400 font-bold">{{ $item->strength }}</td>
                            <td class="px-4 py-3 text-center text-blue-400 font-bold">{{ $item->speed }}</td>
                            <td class="px-4 py-3 text-center text-green-400 font-bold">{{ $item->durability }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1 text-purple-300 text-sm font-semibold">
                                    <flux:icon.users class="size-3.5 text-purple-500" />
                                    {{ $item->user_items_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.items.edit', $item) }}"
                                       class="rounded px-3 py-1 text-xs border border-purple-700 text-purple-300 hover:bg-purple-700 hover:text-white transition">
                                        Bewerken
                                    </a>
                                    <a href="{{ route('admin.items.assign.show', $item) }}"
                                       class="rounded px-3 py-1 text-xs border border-teal-800 text-teal-400 hover:bg-teal-800 hover:text-white transition">
                                        Toekennen
                                    </a>
                                    <form method="POST" action="{{ route('admin.items.destroy', $item) }}"
                                          onsubmit="return confirm('Weet je zeker dat je \'{{ addslashes($item->name) }}\' wilt verwijderen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="rounded px-3 py-1 text-xs border border-red-800 text-red-400 hover:bg-red-800 hover:text-white transition">
                                            Verwijderen
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-purple-500">Geen voorwerpen gevonden.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
