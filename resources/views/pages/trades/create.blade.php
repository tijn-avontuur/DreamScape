<x-layouts::app :title="__('Ruilverzoek sturen')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('trades.index') }}" class="text-purple-500 hover:text-purple-300 transition">
                <flux:icon.arrow-left class="size-5" />
            </a>
            <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">Nieuw Ruilverzoek</h1>
        </div>

        <div class="rounded-2xl border border-purple-900/40 bg-[#1a1035] p-6 sm:p-8 max-w-lg">
            <form method="POST" action="{{ route('trades.store') }}" class="flex flex-col gap-5">
                @csrf

                {{-- US18: andere speler selecteren --}}
                <div class="flex flex-col gap-2">
                    <label for="receiver_id" class="text-xs uppercase tracking-widest text-purple-400">Stuur naar speler</label>
                    <select
                        id="receiver_id"
                        name="receiver_id"
                        class="w-full rounded-lg border border-purple-900/40 bg-[#110826] px-4 py-2 text-sm text-purple-200 focus:border-purple-500 focus:outline-none @error('receiver_id') border-red-500 @enderror">
                        <option value="">— Kies een speler —</option>
                        @foreach($players as $player)
                            <option value="{{ $player->id }}" {{ old('receiver_id') == $player->id ? 'selected' : '' }}>
                                {{ $player->username }}
                            </option>
                        @endforeach
                    </select>
                    @error('receiver_id')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- US18: item uit eigen inventory --}}
                <div class="flex flex-col gap-2">
                    <label for="item_id" class="text-xs uppercase tracking-widest text-purple-400">Item om aan te bieden</label>
                    @if($inventory->isEmpty())
                        <p class="rounded-lg border border-red-900/40 bg-red-900/20 px-4 py-3 text-sm text-red-400">
                            Je hebt geen items in je inventaris om aan te bieden.
                        </p>
                    @else
                        <select
                            id="item_id"
                            name="item_id"
                            class="w-full rounded-lg border border-purple-900/40 bg-[#110826] px-4 py-2 text-sm text-purple-200 focus:border-purple-500 focus:outline-none @error('item_id') border-red-500 @enderror">
                            <option value="">— Kies een item —</option>
                            @foreach($inventory as $entry)
                                <option value="{{ $entry->item->id }}" {{ old('item_id') == $entry->item->id ? 'selected' : '' }}>
                                    {{ $entry->item->name }}
                                    ({{ ucfirst($entry->item->rarity) }})
                                    @if($entry->quantity > 1) ×{{ $entry->quantity }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <p class="text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex gap-3 pt-2">
                    <button
                        type="submit"
                        @if($inventory->isEmpty()) disabled @endif
                        class="rounded-lg border border-teal-700 bg-teal-900/30 px-5 py-2 text-sm text-teal-300 hover:bg-teal-700 hover:text-white transition disabled:opacity-40 disabled:cursor-not-allowed">
                        Ruilverzoek versturen
                    </button>
                    <a href="{{ route('trades.index') }}"
                       class="rounded-lg border border-purple-900/40 bg-purple-900/20 px-5 py-2 text-sm text-purple-400 hover:text-purple-200 transition">
                        Annuleren
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-layouts::app>
