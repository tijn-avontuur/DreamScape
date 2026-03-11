<x-layouts::app :title="'Item Toekennen: ' . $item->name">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-purple-500">
            <a href="{{ route('admin.items.index') }}" class="hover:text-purple-300 transition">Voorwerpen Beheren</a>
            <span>/</span>
            <span class="text-white">Toekennen</span>
        </div>

        <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">Item Toekennen aan Speler</h1>

        <div class="max-w-lg">
            {{-- Item info card --}}
            <div class="mb-4 flex items-center gap-4 rounded-xl border border-purple-900/40 bg-[#1a1035] p-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-500/10 text-amber-400">
                    <flux:icon.archive-box class="size-6" />
                </div>
                <div>
                    <p class="font-semibold text-white">{{ $item->name }}</p>
                    <div class="mt-0.5 flex items-center gap-2">
                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $item->rarityBadgeClass() }}">
                            {{ ucfirst($item->rarity) }}
                        </span>
                        @php
                            $typeLabels = ['weapon' => 'Wapen', 'armor' => 'Uitrusting', 'accessory' => 'Accessoire', 'consumable' => 'Verbruiksartikel', 'other' => 'Overig'];
                        @endphp
                        <span class="text-xs uppercase tracking-widest text-purple-400">
                            {{ $typeLabels[$item->type] ?? ucfirst($item->type) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Assign form --}}
            <form method="POST" action="{{ route('admin.items.assign', $item) }}"
                  class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-6">
                @csrf

                @if(session('success'))
                    <div class="mb-4 rounded-lg border border-green-700/40 bg-green-900/20 px-4 py-3 text-sm text-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 rounded-lg border border-red-700/40 bg-red-900/20 px-4 py-3 text-sm text-red-400">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="mb-6">
                    <label for="user_id" class="mb-1 block text-sm font-medium text-purple-300">
                        Speler kiezen <span class="text-red-400">*</span>
                    </label>
                    @if($players->isEmpty())
                        <p class="text-sm text-purple-500 italic">Geen spelers beschikbaar.</p>
                    @else
                        <select id="user_id" name="user_id" required
                                class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-white focus:border-purple-500 focus:outline-none @error('user_id') border-red-600 @enderror">
                            <option value="">— Selecteer een speler —</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}" {{ old('user_id') == $player->id ? 'selected' : '' }}>
                                    {{ $player->username }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="rounded-lg bg-purple-700 px-5 py-2 text-sm font-medium text-white hover:bg-purple-600 transition disabled:opacity-50"
                            @if($players->isEmpty()) disabled @endif>
                        Toekennen
                    </button>
                    <a href="{{ route('admin.items.index') }}"
                       class="rounded-lg border border-purple-800 px-5 py-2 text-sm text-purple-300 hover:bg-purple-900/30 transition">
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
