@php $editing = $item->exists; @endphp
<x-layouts::app :title="$editing ? 'Voorwerp Bewerken' : 'Nieuw Voorwerp'">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-purple-500">
            <a href="{{ route('admin.items.index') }}" class="hover:text-purple-300 transition">Voorwerpen Beheren</a>
            <span>/</span>
            <span class="text-white">{{ $editing ? 'Bewerken' : 'Nieuw voorwerp' }}</span>
        </div>

        <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">
            {{ $editing ? 'Voorwerp Bewerken' : 'Nieuw Voorwerp Toevoegen' }}
        </h1>

        <form method="POST"
              action="{{ $editing ? route('admin.items.update', $item) : route('admin.items.store') }}"
              class="max-w-2xl">
            @csrf
            @if($editing) @method('PUT') @endif

            <div class="flex flex-col gap-5 rounded-xl border border-purple-900/40 bg-[#1a1035] p-6">

                {{-- Naam --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-purple-300">Naam <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $item->name) }}" required
                           class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-white placeholder-purple-600 focus:border-purple-500 focus:outline-none @error('name') border-red-600 @enderror">
                    @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Beschrijving --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-purple-300">Beschrijving</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-white placeholder-purple-600 focus:border-purple-500 focus:outline-none @error('description') border-red-600 @enderror">{{ old('description', $item->description) }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Type + Zeldzaamheid --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-purple-300">Type <span class="text-red-400">*</span></label>
                        <select name="type" required
                                class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-white focus:border-purple-500 focus:outline-none @error('type') border-red-600 @enderror">
                            @foreach(['weapon' => 'Wapen', 'armor' => 'Uitrusting', 'accessory' => 'Accessoire', 'consumable' => 'Verbruiksartikel', 'other' => 'Overig'] as $val => $label)
                                <option value="{{ $val }}" {{ old('type', $item->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-purple-300">Zeldzaamheid <span class="text-red-400">*</span></label>
                        <select name="rarity" required
                                class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-white focus:border-purple-500 focus:outline-none @error('rarity') border-red-600 @enderror">
                            @foreach(['common' => 'Gewoon', 'uncommon' => 'Ongewoon', 'rare' => 'Zeldzaam', 'epic' => 'Episch', 'legendary' => 'Legendarisch'] as $val => $label)
                                <option value="{{ $val }}" {{ old('rarity', $item->rarity) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('rarity') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Stats: Kracht / Snelheid / Duurzaamheid (0-100) --}}
                <div class="grid grid-cols-3 gap-4">
                    @foreach(['strength' => ['label' => 'Kracht', 'color' => 'text-red-400'], 'speed' => ['label' => 'Snelheid', 'color' => 'text-blue-400'], 'durability' => ['label' => 'Duurzaamheid', 'color' => 'text-green-400']] as $field => $meta)
                        <div>
                            <label class="mb-1 block text-sm font-medium text-purple-300">
                                {{ $meta['label'] }} <span class="text-xs text-purple-600">(0-100)</span>
                            </label>
                            <input type="number" name="{{ $field }}" min="0" max="100"
                                   value="{{ old($field, $item->$field ?? 0) }}" required
                                   class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 {{ $meta['color'] }} font-bold focus:border-purple-500 focus:outline-none @error($field) border-red-600 @enderror">
                            @error($field) <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>

                {{-- Magische eigenschap --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-purple-300">Magische eigenschap</label>
                    <input type="text" name="magic_property" value="{{ old('magic_property', $item->magic_property) }}"
                           placeholder="bijv. Vuurstoot: deelt 20 extra vuurschade"
                           class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-white placeholder-purple-600 focus:border-purple-500 focus:outline-none @error('magic_property') border-red-600 @enderror">
                    @error('magic_property') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Vereist level --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-purple-300">Vereist level <span class="text-red-400">*</span></label>
                    <input type="number" name="required_level" min="1" max="100"
                           value="{{ old('required_level', $item->required_level ?? 1) }}" required
                           class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-white focus:border-purple-500 focus:outline-none @error('required_level') border-red-600 @enderror">
                    @error('required_level') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="rounded-lg bg-purple-700 px-6 py-2 font-medium text-white hover:bg-purple-600 transition">
                        {{ $editing ? 'Wijzigingen opslaan' : 'Voorwerp aanmaken' }}
                    </button>
                    <a href="{{ route('admin.items.index') }}"
                       class="rounded-lg border border-purple-800 px-6 py-2 text-sm text-purple-400 hover:text-white transition">
                        Annuleren
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>
