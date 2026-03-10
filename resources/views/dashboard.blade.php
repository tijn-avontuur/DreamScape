<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Welcome Banner --}}
        <div class="relative overflow-hidden rounded-2xl border border-purple-900/40 bg-gradient-to-r from-[#1a1035] via-[#261850] to-[#1a1035] px-8 py-6">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-amber-400 via-transparent to-transparent pointer-events-none"></div>
            <div class="relative">
                <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">
                    Welkom terug, {{ auth()->user()->username }}
                </h1>
                <p class="mt-1 text-sm text-purple-300">
                    @if(auth()->user()->isAdmin())
                        Je hebt beheerderstoegang tot DreamScape Interactive.
                    @else
                        Klaar om de wereld van DreamScape te ontdekken? Bekijk je inventaris en ga handelen.
                    @endif
                </p>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500/10 text-amber-400">
                        <flux:icon.archive-box class="size-5" />
                    </div>
                    <div>
                        <p class="text-xs text-purple-400 uppercase tracking-wider">Mijn Voorwerpen</p>
                        <p class="text-2xl font-bold text-white">{{ auth()->user()->inventories()->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-500/10 text-teal-400">
                        <flux:icon.arrows-right-left class="size-5" />
                    </div>
                    <div>
                        <p class="text-xs text-purple-400 uppercase tracking-wider">Actieve Ruilhandelingen</p>
                        <p class="text-2xl font-bold text-white">
                            {{ auth()->user()->tradesAsSender()->where('status','pending')->count() + auth()->user()->tradesAsReceiver()->where('status','pending')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500/10 text-blue-400">
                        <flux:icon.bell class="size-5" />
                    </div>
                    <div>
                        <p class="text-xs text-purple-400 uppercase tracking-wider">Meldingen</p>
                        <p class="text-2xl font-bold text-white">{{ auth()->user()->gameNotifications()->where('is_read', false)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-500/10 text-purple-400">
                        <flux:icon.book-open class="size-5" />
                    </div>
                    <div>
                        <p class="text-xs text-purple-400 uppercase tracking-wider">Totaal Voorwerpen</p>
                        <p class="text-2xl font-bold text-white">{{ \App\Models\Item::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions + Recent activity --}}
        <div class="grid gap-4 lg:grid-cols-2">
            {{-- Quick Actions --}}
            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-6">
                <h2 class="font-display text-sm font-semibold uppercase tracking-widest text-amber-400 mb-4">Snelle Acties</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('items.index') }}" wire:navigate
                       class="flex flex-col items-center gap-2 rounded-lg border border-purple-800/40 bg-purple-900/20 p-4 text-center transition hover:border-amber-500/40 hover:bg-amber-500/5">
                        <flux:icon.book-open class="size-6 text-amber-400" />
                        <span class="text-xs font-medium text-purple-200">Catalogus Bekijken</span>
                    </a>
                    <a href="{{ route('inventory.index') }}" wire:navigate
                       class="flex flex-col items-center gap-2 rounded-lg border border-purple-800/40 bg-purple-900/20 p-4 text-center transition hover:border-teal-500/40 hover:bg-teal-500/5">
                        <flux:icon.archive-box class="size-6 text-teal-400" />
                        <span class="text-xs font-medium text-purple-200">Mijn Inventaris</span>
                    </a>
                    <a href="{{ route('trades.index') }}" wire:navigate
                       class="flex flex-col items-center gap-2 rounded-lg border border-purple-800/40 bg-purple-900/20 p-4 text-center transition hover:border-blue-500/40 hover:bg-blue-500/5">
                        <flux:icon.arrows-right-left class="size-6 text-blue-400" />
                        <span class="text-xs font-medium text-purple-200">Ruilpost</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" wire:navigate
                       class="flex flex-col items-center gap-2 rounded-lg border border-purple-800/40 bg-purple-900/20 p-4 text-center transition hover:border-purple-500/40 hover:bg-purple-500/5">
                        <flux:icon.user class="size-6 text-purple-400" />
                        <span class="text-xs font-medium text-purple-200">Mijn Profiel</span>
                    </a>
                </div>
            </div>

            {{-- Incoming Trade Requests --}}
            <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-6">
                <h2 class="font-display text-sm font-semibold uppercase tracking-widest text-amber-400 mb-4">Inkomende Ruilverzoeken</h2>
                @php
                    $incomingTrades = auth()->user()->tradesAsReceiver()->where('status','pending')->with(['sender'])->latest()->take(4)->get();
                @endphp
                @if($incomingTrades->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <flux:icon.arrows-right-left class="size-8 text-purple-700 mb-2" />
                        <p class="text-sm text-purple-500">Geen openstaande ruilverzoeken.</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($incomingTrades as $trade)
                        <div class="flex items-center justify-between rounded-lg border border-purple-800/30 bg-purple-900/20 px-4 py-2">
                            <div>
                                <p class="text-sm font-medium text-white">{{ $trade->sender->username }}</p>
                                <p class="text-xs text-purple-400">wil met je ruilen</p>
                            </div>
                            <a href="{{ route('trades.index') }}" wire:navigate class="text-xs text-amber-400 hover:text-amber-300">Bekijk →</a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-layouts::app>

