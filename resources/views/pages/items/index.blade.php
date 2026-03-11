<x-layouts::app :title="__('Voorwerpen Catalogus')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">Voorwerpen Catalogus</h1>
            <span class="text-sm text-purple-400">{{ $items->count() }} {{ $items->count() === 1 ? 'item' : 'items' }} gevonden</span>
        </div>

        {{-- Filter form (US12 – zoeken, US13 – type, US14 – zeldzaamheid) --}}
        <form
            id="filter-form"
            method="GET"
            action="{{ route('items.index') }}"
            x-data="{ selectedType: @js($type) }"
            class="flex flex-col gap-4">

            {{-- Hidden type input (updated by type buttons) --}}
            <input type="hidden" name="type" x-model="selectedType" />

            {{-- US12: Zoekveld --}}
            <div class="flex gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Zoek op naam..."
                    class="flex-1 rounded-lg border border-purple-900/40 bg-[#1a1035] px-4 py-2 text-sm text-white placeholder-purple-600 focus:border-purple-500 focus:outline-none"
                />
                <button
                    type="submit"
                    class="rounded-lg border border-purple-700 bg-purple-900/30 px-4 py-2 text-sm text-purple-300 hover:bg-purple-700 hover:text-white transition">
                    Zoeken
                </button>
                @if($search || $type || $rarity)
                    <a href="{{ route('items.index') }}"
                       class="rounded-lg border border-red-900/40 bg-red-900/20 px-4 py-2 text-sm text-red-400 hover:bg-red-900/40 hover:text-red-300 transition">
                        Wis filters
                    </a>
                @endif
            </div>

            {{-- US13: Type filter buttons --}}
            <div class="flex flex-wrap gap-2">
                @php
                    $typeOptions = ['' => 'Alle types', 'weapon' => 'Wapens', 'armor' => 'Uitrusting', 'accessory' => 'Accessoires', 'consumable' => 'Verbruiksartikelen', 'other' => 'Overig'];
                @endphp
                @foreach($typeOptions as $value => $label)
                    <button
                        type="button"
                        x-on:click="selectedType = '{{ $value }}'; $nextTick(() => $el.closest('form').submit())"
                        :class="selectedType === '{{ $value }}'
                            ? 'bg-purple-700 border-purple-500 text-white'
                            : 'bg-[#1a1035] border-purple-900/40 text-purple-400 hover:border-purple-600 hover:text-purple-200'"
                        class="rounded-lg border px-4 py-1.5 text-sm font-medium transition">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- US14: Zeldzaamheid filter --}}
            <div class="flex items-center gap-3">
                <label class="text-xs uppercase tracking-widest text-purple-500">Zeldzaamheid</label>
                <select
                    name="rarity"
                    x-on:change="$el.form.submit()"
                    class="rounded-lg border border-purple-900/40 bg-[#1a1035] px-3 py-1.5 text-sm text-purple-300 focus:border-purple-500 focus:outline-none">
                    @php
                        $rarityOptions = ['' => 'Alle zeldzaamheid', 'common' => 'Common', 'uncommon' => 'Uncommon', 'rare' => 'Rare', 'epic' => 'Epic', 'legendary' => 'Legendary'];
                    @endphp
                    @foreach($rarityOptions as $value => $label)
                        <option value="{{ $value }}" {{ $rarity === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- Item grid --}}
        @if($items->isEmpty())
            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-12 text-center">
                <flux:icon.magnifying-glass class="mx-auto mb-3 size-12 opacity-30 text-purple-400" />
                <p class="text-purple-400">Geen items gevonden voor je zoekopdracht.</p>
                <a href="{{ route('items.index') }}" class="mt-4 inline-block rounded-lg border border-purple-700 bg-purple-900/30 px-4 py-2 text-sm text-purple-300 hover:bg-purple-700 hover:text-white transition">
                    Alle items bekijken
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @php
                    $typeLabels = ['weapon' => 'Wapen', 'armor' => 'Uitrusting', 'accessory' => 'Accessoire', 'consumable' => 'Verbruiksartikel', 'other' => 'Overig'];
                @endphp
                @foreach($items as $item)
                    <div class="flex flex-col gap-3 rounded-xl border border-purple-900/40 bg-[#1a1035] p-4 hover:border-purple-600 transition">

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
        @endif

    </div>
</x-layouts::app>
