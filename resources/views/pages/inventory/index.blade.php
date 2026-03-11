<x-layouts::app :title="__('Mijn Inventaris')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">Mijn Inventaris</h1>
            <span class="text-sm text-purple-400">{{ $entries->count() }} {{ $entries->count() === 1 ? 'item' : 'items' }}</span>
        </div>

        @if($entries->isEmpty())
            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-12 text-center">
                <flux:icon.archive-box class="mx-auto mb-3 size-12 opacity-30 text-purple-400" />
                <p class="text-purple-400">Je inventaris is leeg.</p>
                <a href="{{ route('items.index') }}" class="mt-4 inline-block rounded-lg border border-purple-700 bg-purple-900/30 px-4 py-2 text-sm text-purple-300 hover:bg-purple-700 hover:text-white transition">
                    Bekijk de catalogus
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($entries as $entry)
                    @php $item = $entry->item; @endphp
                    <div class="flex flex-col gap-3 rounded-xl border border-purple-900/40 bg-[#1a1035] p-4 hover:border-purple-600 transition">

                        {{-- Name, rarity, quantity --}}
                        <div class="flex items-start justify-between gap-2">
                            <a href="{{ route('items.show', $item) }}" class="font-bold text-white hover:text-amber-400 transition leading-tight">
                                {{ $item->name }}
                            </a>
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-semibold {{ $item->rarityBadgeClass() }}">
                                {{ ucfirst($item->rarity) }}
                            </span>
                        </div>

                        {{-- Type + quantity --}}
                        @php
                            $typeLabels = ['weapon' => 'Wapen', 'armor' => 'Uitrusting', 'accessory' => 'Accessoire', 'consumable' => 'Verbruiksartikel', 'other' => 'Overig'];
                        @endphp
                        <div class="flex items-center justify-between text-xs">
                            <span class="uppercase tracking-widest text-purple-400">
                                {{ $typeLabels[$item->type] ?? ucfirst($item->type) }}
                            </span>
                            @if($entry->quantity > 1)
                                <span class="rounded-full bg-purple-900/50 px-2 py-0.5 text-purple-300">
                                    ×{{ $entry->quantity }}
                                </span>
                            @endif
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-3 gap-1 text-xs">
                            <div class="flex flex-col items-center rounded-lg bg-purple-900/20 py-1.5 px-1">
                                <span class="font-bold text-red-400">{{ $item->strength }}</span>
                                <span class="text-purple-500">Kracht</span>
                            </div>
                            <div class="flex flex-col items-center rounded-lg bg-purple-900/20 py-1.5 px-1">
                                <span class="font-bold text-blue-400">{{ $item->speed }}</span>
                                <span class="text-purple-500">Snelheid</span>
                            </div>
                            <div class="flex flex-col items-center rounded-lg bg-purple-900/20 py-1.5 px-1">
                                <span class="font-bold text-green-400">{{ $item->durability }}</span>
                                <span class="text-purple-500">Duurzaamheid</span>
                            </div>
                        </div>

                        {{-- Magic property --}}
                        @if($item->magic_property)
                            <div class="rounded-lg bg-amber-900/10 border border-amber-700/30 p-2 text-xs text-amber-300">
                                ✨ {{ $item->magic_property }}
                            </div>
                        @endif

                        {{-- Obtained date --}}
                        <div class="mt-auto text-xs text-purple-600">
                            Verkregen: {{ $entry->obtained_at->format('d-m-Y') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::app>
