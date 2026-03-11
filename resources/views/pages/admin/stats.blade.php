<x-layouts::app :title="__('Beheer — Statistieken')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">Statistieken</h1>
        </div>

        {{-- Summary cards --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500/10 text-blue-400">
                        <flux:icon.users class="size-5" />
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider text-purple-400">Actieve Spelers</p>
                        <p class="text-2xl font-bold text-white">{{ $totalPlayers }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-500/10 text-purple-400">
                        <flux:icon.archive-box class="size-5" />
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider text-purple-400">Unieke Voorwerpen</p>
                        <p class="text-2xl font-bold text-white">{{ $totalItemTypes }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500/10 text-amber-400">
                        <flux:icon.chart-bar class="size-5" />
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider text-purple-400">Totaal Uitgedeeld</p>
                        <p class="text-2xl font-bold text-white">{{ $totalAssigned }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Items per owner table --}}
        <div class="overflow-x-auto rounded-xl border border-purple-900/40 bg-[#1a1035]">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-purple-900/40 text-left text-xs uppercase tracking-widest text-purple-500">
                        <th class="px-4 py-3">Voorwerp</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Zeldzaamheid</th>
                        <th class="px-4 py-3 text-center">Aantal Bezitters</th>
                        <th class="px-4 py-3 text-center">Actie</th>
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
                            <td class="px-4 py-3 text-center">
                                @if($item->user_items_count > 0)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-purple-900/40 px-3 py-0.5 text-sm font-bold text-purple-200">
                                        <flux:icon.users class="size-3.5" />
                                        {{ $item->user_items_count }}
                                    </span>
                                @else
                                    <span class="text-purple-600 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.items.assign.show', $item) }}"
                                   class="rounded px-3 py-1 text-xs border border-teal-800 text-teal-400 hover:bg-teal-800 hover:text-white transition">
                                    Toekennen
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-purple-500">Geen voorwerpen gevonden.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
