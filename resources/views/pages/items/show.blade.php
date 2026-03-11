<x-layouts::app :title="$item->name">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-purple-500">
            <a href="{{ route('items.index') }}" class="hover:text-purple-300 transition">Voorwerpen Catalogus</a>
            <span>/</span>
            <span class="text-white">{{ $item->name }}</span>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Left: Main info --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- Title card --}}
                <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $item->name }}</h1>
                            <div class="mt-1 flex items-center gap-3">
                                @php
                                    $typeLabels = ['weapon' => 'Wapen', 'armor' => 'Uitrusting', 'accessory' => 'Accessoire', 'consumable' => 'Verbruiksartikel', 'other' => 'Overig'];
                                @endphp
                                <span class="text-xs uppercase tracking-widest text-purple-400">
                                    {{ $typeLabels[$item->type] ?? ucfirst($item->type) }}
                                </span>
                                <span class="rounded-full px-3 py-0.5 text-xs font-semibold {{ $item->rarityBadgeClass() }}">
                                    {{ ucfirst($item->rarity) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right text-sm text-purple-500">
                            Vereist level {{ $item->required_level }}
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($item->description)
                        <p class="mt-4 leading-relaxed text-purple-300">{{ $item->description }}</p>
                    @endif
                </div>

                {{-- Magic property --}}
                @if($item->magic_property)
                    <div class="rounded-xl border border-amber-700/40 bg-amber-900/10 p-5">
                        <div class="mb-2 flex items-center gap-2 text-amber-400">
                            <flux:icon.sparkles class="size-5" />
                            <span class="font-semibold">Magische eigenschap</span>
                        </div>
                        <p class="text-amber-300">{{ $item->magic_property }}</p>
                    </div>
                @else
                    <div class="rounded-xl border border-purple-900/30 bg-[#1a1035] p-5 text-center text-purple-600 text-sm">
                        Geen magische eigenschap
                    </div>
                @endif
            </div>

            {{-- Right: Stats --}}
            <div class="flex flex-col gap-4">
                <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-5">
                    <h2 class="mb-4 font-semibold text-white">Statistieken</h2>

                    <div class="flex flex-col gap-3">
                        {{-- Strength --}}
                        <div>
                            <div class="mb-1 flex justify-between text-sm">
                                <span class="text-purple-400">Kracht</span>
                                <span class="font-bold text-red-400">{{ $item->strength }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-purple-900/40">
                                <div class="h-2 rounded-full bg-red-500" style="width: {{ $item->strength }}%"></div>
                            </div>
                        </div>

                        {{-- Speed --}}
                        <div>
                            <div class="mb-1 flex justify-between text-sm">
                                <span class="text-purple-400">Snelheid</span>
                                <span class="font-bold text-blue-400">{{ $item->speed }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-purple-900/40">
                                <div class="h-2 rounded-full bg-blue-500" style="width: {{ $item->speed }}%"></div>
                            </div>
                        </div>

                        {{-- Durability --}}
                        <div>
                            <div class="mb-1 flex justify-between text-sm">
                                <span class="text-purple-400">Duurzaamheid</span>
                                <span class="font-bold text-green-400">{{ $item->durability }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-purple-900/40">
                                <div class="h-2 rounded-full bg-green-500" style="width: {{ $item->durability }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info tabel --}}
                <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-5">
                    <h2 class="mb-4 font-semibold text-white">Informatie</h2>
                    <div class="flex flex-col gap-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-purple-500">Naam</span>
                            <span class="text-white">{{ $item->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-purple-500">Type</span>
                            <span class="text-white">{{ $typeLabels[$item->type] ?? ucfirst($item->type) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-purple-500">Zeldzaamheid</span>
                            <span class="{{ $item->rarityColor() }} font-medium">{{ ucfirst($item->rarity) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-purple-500">Vereist level</span>
                            <span class="text-white">{{ $item->required_level }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('items.index') }}"
                   class="rounded-lg border border-purple-700 bg-purple-900/30 py-2 text-center text-sm text-purple-300 hover:bg-purple-700 hover:text-white transition">
                    ← Terug naar catalogus
                </a>
            </div>
        </div>
    </div>
</x-layouts::app>
