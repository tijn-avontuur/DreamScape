<x-layouts::app :title="__('Ruilpost')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">Ruilpost</h1>
            <a href="{{ route('trades.create') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-teal-700 bg-teal-900/30 px-4 py-2 text-sm text-teal-300 hover:bg-teal-700 hover:text-white transition">
                <flux:icon.plus class="size-4" />
                Nieuw ruilverzoek
            </a>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="rounded-lg border border-teal-700/40 bg-teal-900/20 px-4 py-3 text-sm text-teal-300">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="rounded-lg border border-purple-700/40 bg-purple-900/20 px-4 py-3 text-sm text-purple-300">{{ session('info') }}</div>
        @endif

        {{-- US19: Inkomende verzoeken --}}
        <div class="rounded-2xl border border-purple-900/40 bg-[#1a1035] p-6">
            <h2 class="font-display text-sm font-semibold uppercase tracking-widest text-amber-400 mb-4">
                Inkomende Verzoeken
                @if($incoming->where('status','pending')->count())
                    <span class="ml-2 rounded-full bg-amber-500/20 px-2 py-0.5 text-xs text-amber-400">{{ $incoming->where('status','pending')->count() }}</span>
                @endif
            </h2>

            @if($incoming->isEmpty())
                <p class="text-sm text-purple-500 py-4 text-center">Geen inkomende verzoeken.</p>
            @else
                <div class="space-y-3">
                    @foreach($incoming as $trade)
                        @php $item = $trade->tradeItems->first()?->item; @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-xl border border-purple-800/30 bg-purple-900/20 px-4 py-3">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-semibold text-white">{{ $trade->sender->username }}</span>
                                <span class="text-xs text-purple-400">
                                    biedt aan: <span class="text-purple-200">{{ $item?->name ?? '–' }}</span>
                                </span>
                                <span class="text-xs text-purple-600">{{ $trade->created_at->format('d-m-Y H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                {{-- US19: status zichtbaar --}}
                                @if($trade->status === 'pending')
                                    <form method="POST" action="{{ route('trades.accept', $trade) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="rounded-lg border border-teal-700 bg-teal-900/30 px-3 py-1.5 text-xs text-teal-300 hover:bg-teal-700 hover:text-white transition">
                                            Accepteren
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('trades.reject', $trade) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="rounded-lg border border-red-900/40 bg-red-900/20 px-3 py-1.5 text-xs text-red-400 hover:bg-red-900/40 hover:text-red-300 transition">
                                            Weigeren
                                        </button>
                                    </form>
                                @elseif($trade->status === 'accepted')
                                    <span class="rounded-full bg-teal-500/20 px-3 py-1 text-xs text-teal-400 ring-1 ring-teal-500/30">Geaccepteerd</span>
                                @else
                                    <span class="rounded-full bg-red-500/20 px-3 py-1 text-xs text-red-400 ring-1 ring-red-500/30">Geweigerd</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Uitgaande verzoeken --}}
        <div class="rounded-2xl border border-purple-900/40 bg-[#1a1035] p-6">
            <h2 class="font-display text-sm font-semibold uppercase tracking-widest text-amber-400 mb-4">Uitgaande Verzoeken</h2>

            @if($outgoing->isEmpty())
                <p class="text-sm text-purple-500 py-4 text-center">Je hebt nog geen verzoeken verstuurd.</p>
            @else
                <div class="space-y-3">
                    @foreach($outgoing as $trade)
                        @php $item = $trade->tradeItems->first()?->item; @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-xl border border-purple-800/30 bg-purple-900/20 px-4 py-3">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-semibold text-white">Aan: {{ $trade->receiver->username }}</span>
                                <span class="text-xs text-purple-400">
                                    aangeboden: <span class="text-purple-200">{{ $item?->name ?? '–' }}</span>
                                </span>
                                <span class="text-xs text-purple-600">{{ $trade->created_at->format('d-m-Y H:i') }}</span>
                            </div>
                            <div>
                                @if($trade->status === 'pending')
                                    <span class="rounded-full bg-amber-500/20 px-3 py-1 text-xs text-amber-400 ring-1 ring-amber-500/30">In behandeling</span>
                                @elseif($trade->status === 'accepted')
                                    <span class="rounded-full bg-teal-500/20 px-3 py-1 text-xs text-teal-400 ring-1 ring-teal-500/30">Geaccepteerd</span>
                                @else
                                    <span class="rounded-full bg-red-500/20 px-3 py-1 text-xs text-red-400 ring-1 ring-red-500/30">Geweigerd</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-layouts::app>
