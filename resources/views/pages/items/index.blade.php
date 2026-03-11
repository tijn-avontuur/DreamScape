<x-layouts::app :title="__('Voorwerpen Catalogus')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">Voorwerpen Catalogus</h1>
            <span class="text-sm text-purple-400">{{ $items->count() }} items beschikbaar</span>
        </div>

        {{-- Type filter --}}
        <div class="flex flex-wrap gap-2" x-data="{ filter: 'all' }">
            @foreach(['all' => 'Alles', 'weapon' => 'Wapens', 'armor' => 'Uitrusting', 'accessory' => 'Accessoires', 'consumable' => 'Verbruiksartikelen'] as $value => $label)
                <button
                    x-on:click="filter = '{{ $value }}'"
                    x-bind:class="filter === '{{ $value }}'
                        ? 'bg-purple-700 border-purple-500 text-white'
                        : 'bg-[#1a1035] border-purple-900/40 text-purple-400 hover:border-purple-600'"
                    class="rounded-lg border px-4 py-1.5 text-sm font-medium transition">
                    {{ $label }}
                </button>
            @endforeach

            {{-- Item grid --}}
            <div class="mt-2 grid w-full grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($items as $item)
                    <div
                        x-show="filter === 'all' || filter === '{{ $item->type }}'"
                        x-transition
                        class="flex flex-col gap-3 rounded-xl border border-purple-900/40 bg-[#1a1035] p-4 hover:border-purple-600 transition">

                        {{-- Name & rarity badge --}}
                        <div class="flex items-start justify-between gap-2">
                            <a href="{{ route('items.show', $item) }}" class="font-bold text-white hover:text-amber-400 transition leading-tight">
                                {{ $item->name }}
                            </a>
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-semibold {{ $item->rarityBadgeClass() }}">
                                {{ ucfirst($item->rarity) }}
                            </span>
                        </div>

                        {{-- Type --}}
                        <div class="text-xs text-purple-400 uppercase tracking-widest">
                            @php
                                $typeLabels = ['weapon' => 'Wapen', 'armor' => 'Uitrusting', 'accessory' => 'Accessoire', 'consumable' => 'Verbruiksartikel', 'other' => 'Overig'];
                            @endphp
                            {{ $typeLabels[$item->type] ?? ucfirst($item->type) }}
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-3 gap-1 text-xs">
                            <div class="flex flex-col items-center rounded-lg bg-purple-900/20 py-1.5 px-1">
                                <span class="text-red-400 font-bold">{{ $item->strength }}</span>
                                <span class="text-purple-500">Kracht</span>
                            </div>
                            <div class="flex flex-col items-center rounded-lg bg-purple-900/20 py-1.5 px-1">
                                <span class="text-blue-400 font-bold">{{ $item->speed }}</span>
                                <span class="text-purple-500">Snelheid</span>
                            </div>
                            <div class="flex flex-col items-center rounded-lg bg-purple-900/20 py-1.5 px-1">
                                <span class="text-green-400 font-bold">{{ $item->durability }}</span>
                                <span class="text-purple-500">Duurzaamheid</span>
                            </div>
                        </div>

                        <a href="{{ route('items.show', $item) }}"
                           class="mt-auto text-center rounded-lg border border-purple-700 bg-purple-900/30 py-1.5 text-sm text-purple-300 hover:bg-purple-700 hover:text-white transition">
                            Details bekijken
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::app>
