<!DOCTYPE html>
<html lang="nl" class="dark">
    <head>
        @include('partials.head')
        <title>DreamScape Interactive</title>
    </head>
    <body class="min-h-screen bg-[#0f0a1e] text-gray-100 antialiased">

        {{-- Navigation --}}
        <header class="border-b border-purple-900/40 bg-[#1a1035]/80 backdrop-blur-sm sticky top-0 z-50">
            <div class="mx-auto max-w-7xl px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <x-app-logo-icon class="size-8 fill-current text-amber-400" />
                    <span class="font-display text-xl font-bold tracking-wide text-amber-400">DreamScape</span>
                </div>
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-5 py-2 text-sm font-semibold text-[#0f0a1e] transition hover:bg-amber-400">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 rounded-lg border border-purple-700/60 px-5 py-2 text-sm font-medium text-purple-200 transition hover:border-amber-500/60 hover:text-amber-300">
                            Inloggen
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-5 py-2 text-sm font-semibold text-[#0f0a1e] transition hover:bg-amber-400">
                                Registreren
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        {{-- Hero Section --}}
        <section class="relative overflow-hidden py-24 md:py-36">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-purple-900/40 via-transparent to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative mx-auto max-w-4xl px-6 text-center">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-4 py-1.5 text-xs font-medium text-amber-400 uppercase tracking-widest">
                    ✦ Welkom bij DreamScape Interactive
                </div>

                <h1 class="font-display text-5xl font-bold tracking-tight text-white md:text-7xl">
                    Beheer jouw
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-400"> Legendarische</span>
                    Inventaris
                </h1>

                <p class="mt-6 text-lg text-purple-300 md:text-xl max-w-2xl mx-auto">
                    Ontdek zeldzame voorwerpen, beheer je inventaris en verhandel met andere spelers in de wereld van DreamScape Interactive.
                </p>

                <div class="mt-10 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-8 py-3.5 text-base font-semibold text-[#0f0a1e] transition hover:bg-amber-400 shadow-lg shadow-amber-500/20">
                            Naar Dashboard →
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-8 py-3.5 text-base font-semibold text-[#0f0a1e] transition hover:bg-amber-400 shadow-lg shadow-amber-500/20">
                            Gratis Beginnen →
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-purple-700/60 px-8 py-3.5 text-base font-medium text-purple-200 transition hover:border-amber-500/60 hover:text-amber-300">
                            Al een account? Inloggen
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section class="py-20 border-t border-purple-900/40">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center mb-14">
                    <h2 class="font-display text-3xl font-bold text-white md:text-4xl">Alles wat je nodig hebt</h2>
                    <p class="mt-3 text-purple-400">Van catalogus tot ruilhandel — alles op één plek.</p>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- Feature 1 --}}
                    <div class="group rounded-2xl border border-purple-900/40 bg-[#1a1035] p-6 transition hover:border-amber-500/40">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400 transition group-hover:bg-amber-500/20">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-white">Voorwerpen Catalogus</h3>
                        <p class="text-sm text-purple-400">Blader door honderden unieke wapens, rustingen en accessoires met gedetailleerde statistieken.</p>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="group rounded-2xl border border-purple-900/40 bg-[#1a1035] p-6 transition hover:border-teal-500/40">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-teal-500/10 text-teal-400 transition group-hover:bg-teal-500/20">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-white">Inventarisbeheer</h3>
                        <p class="text-sm text-purple-400">Beheer al je verzamelde voorwerpen op één overzichtelijke plek, gesorteerd en gefilterd naar wens.</p>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="group rounded-2xl border border-purple-900/40 bg-[#1a1035] p-6 transition hover:border-blue-500/40">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/10 text-blue-400 transition group-hover:bg-blue-500/20">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-white">Ruilhandel</h3>
                        <p class="text-sm text-purple-400">Stuur ruilverzoeken naar andere spelers en onderhandel over de beste deals in het spel.</p>
                    </div>

                    {{-- Feature 4 --}}
                    <div class="group rounded-2xl border border-purple-900/40 bg-[#1a1035] p-6 transition hover:border-purple-500/40">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500/10 text-purple-400 transition group-hover:bg-purple-500/20">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-white">Meldingen</h3>
                        <p class="text-sm text-purple-400">Ontvang directe meldingen wanneer iemand een ruilverzoek stuurt of een transactie accepteert.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Rarities Section --}}
        <section class="py-20 border-t border-purple-900/40">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center mb-14">
                    <h2 class="font-display text-3xl font-bold text-white md:text-4xl">Zeldzaamheidsklassen</h2>
                    <p class="mt-3 text-purple-400">Van gewoon tot legendarisch — elk voorwerp heeft zijn eigen kracht.</p>
                </div>

                <div class="flex flex-wrap justify-center gap-4">
                    <span class="rounded-full border border-gray-700/60 bg-gray-800/40 px-5 py-2 text-sm font-medium text-gray-300">⬜ Gewoon</span>
                    <span class="rounded-full border border-green-700/60 bg-green-900/20 px-5 py-2 text-sm font-medium text-green-300">🟩 Ongewoon</span>
                    <span class="rounded-full border border-blue-700/60 bg-blue-900/20 px-5 py-2 text-sm font-medium text-blue-300">🟦 Zeldzaam</span>
                    <span class="rounded-full border border-purple-700/60 bg-purple-900/20 px-5 py-2 text-sm font-medium text-purple-300">🟪 Episch</span>
                    <span class="rounded-full border border-amber-700/60 bg-amber-900/20 px-5 py-2 text-sm font-medium text-amber-300">🟨 Legendarisch</span>
                </div>
            </div>
        </section>

        {{-- CTA Section --}}
        @guest
        <section class="py-20 border-t border-purple-900/40">
            <div class="mx-auto max-w-3xl px-6 text-center">
                <h2 class="font-display text-3xl font-bold text-white md:text-4xl">Klaar om te beginnen?</h2>
                <p class="mt-4 text-purple-400 text-lg">Maak gratis een account aan en begin vandaag nog met het verkennen van DreamScape Interactive.</p>
                <div class="mt-8 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-8 py-3.5 text-base font-semibold text-[#0f0a1e] transition hover:bg-amber-400 shadow-lg shadow-amber-500/20">
                        Account Aanmaken →
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-purple-700/60 px-8 py-3.5 text-base font-medium text-purple-200 transition hover:border-amber-500/60 hover:text-amber-300">
                        Inloggen
                    </a>
                </div>
            </div>
        </section>
        @endguest

        {{-- Footer --}}
        <footer class="border-t border-purple-900/40 py-8">
            <div class="mx-auto max-w-7xl px-6 text-center text-sm text-purple-600">
                &copy; {{ date('Y') }} DreamScape Interactive. Alle rechten voorbehouden.
            </div>
        </footer>

        @fluxScripts
    </body>
</html>

