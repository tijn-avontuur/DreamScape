<x-layouts::app :title="__('Mijn Profiel')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">Mijn Profiel</h1>
            <a href="{{ route('profile.edit') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-purple-700 bg-purple-900/30 px-4 py-2 text-sm text-purple-300 hover:bg-purple-700 hover:text-white transition">
                <flux:icon.pencil-square class="size-4" />
                Profiel aanpassen
            </a>
        </div>

        {{-- Profile card --}}
        <div class="rounded-2xl border border-purple-900/40 bg-[#1a1035] p-6 sm:p-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:gap-8">

                {{-- Avatar --}}
                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-purple-700 to-indigo-800 text-2xl font-bold text-white shadow-lg">
                    {{ strtoupper(substr($user->username, 0, 2)) }}
                </div>

                {{-- Details --}}
                <div class="flex flex-col gap-4 flex-1">

                    {{-- Username (US3: profielpagina laat gebruikersnaam zien) --}}
                    <div>
                        <p class="text-xs uppercase tracking-widest text-purple-400 mb-1">Gebruikersnaam</p>
                        <p class="text-xl font-bold text-white">{{ $user->username }}</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <p class="text-xs uppercase tracking-widest text-purple-400 mb-1">E-mailadres</p>
                        <p class="text-white">{{ $user->email }}</p>
                    </div>

                    {{-- Role --}}
                    <div>
                        <p class="text-xs uppercase tracking-widest text-purple-400 mb-1">Rol</p>
                        @if($user->isAdmin())
                            <span class="inline-flex items-center rounded-full bg-amber-500/20 px-3 py-1 text-xs font-semibold text-amber-400 ring-1 ring-amber-500/40">
                                Beheerder
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-purple-500/20 px-3 py-1 text-xs font-semibold text-purple-300 ring-1 ring-purple-500/40">
                                Speler
                            </span>
                        @endif
                    </div>

                    {{-- Stats --}}
                    <div class="mt-2 flex gap-6 text-sm">
                        <div class="flex flex-col items-center rounded-xl border border-purple-900/40 bg-purple-900/20 px-5 py-3 text-center">
                            <span class="text-2xl font-bold text-amber-400">{{ $user->inventories()->count() }}</span>
                            <span class="text-xs text-purple-400 mt-1">Voorwerpen</span>
                        </div>
                        <div class="flex flex-col items-center rounded-xl border border-purple-900/40 bg-purple-900/20 px-5 py-3 text-center">
                            <span class="text-2xl font-bold text-teal-400">
                                {{ $user->tradesAsSender()->count() + $user->tradesAsReceiver()->count() }}
                            </span>
                            <span class="text-xs text-purple-400 mt-1">Ruilhandelingen</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts::app>
