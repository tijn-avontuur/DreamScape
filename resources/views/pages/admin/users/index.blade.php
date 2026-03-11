<x-layouts::app :title="__('Beheer — Gebruikers Beheren')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        <h1 class="font-display text-2xl font-bold tracking-wide text-amber-400">Gebruikers Beheren</h1>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="rounded-lg border border-green-700/40 bg-green-900/20 px-4 py-3 text-green-400 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-lg border border-red-700/40 bg-red-900/20 px-4 py-3 text-red-400 text-sm">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Gebruikerslijst --}}
            <div class="lg:col-span-2">
                <div class="overflow-x-auto rounded-xl border border-purple-900/40 bg-[#1a1035]">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-purple-900/40 text-left text-xs uppercase tracking-widest text-purple-500">
                                <th class="px-4 py-3">Gebruikersnaam</th>
                                <th class="px-4 py-3">E-mail</th>
                                <th class="px-4 py-3">Rol</th>
                                <th class="px-4 py-3 text-center">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-purple-900/30">
                            @foreach($users as $user)
                                <tr class="hover:bg-purple-900/10 transition">
                                    <td class="px-4 py-3 font-medium text-white">{{ $user->username }}</td>
                                    <td class="px-4 py-3 text-purple-400">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                            {{ $user->role?->name === 'admin' ? 'bg-amber-900/40 border border-amber-700/40 text-amber-400' : 'bg-purple-900/40 border border-purple-700/40 text-purple-300' }}">
                                            {{ $user->role?->name === 'admin' ? 'Beheerder' : 'Speler' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  onsubmit="return confirm('Gebruiker \'{{ addslashes($user->username) }}\' verwijderen?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="rounded px-3 py-1 text-xs border border-red-800 text-red-400 hover:bg-red-800 hover:text-white transition">
                                                    Verwijderen
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-purple-700">Jijzelf</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Nieuw account formulier --}}
            <div>
                <div class="rounded-xl border border-purple-900/40 bg-[#1a1035] p-5">
                    <h2 class="mb-4 font-semibold text-white">Nieuw account aanmaken</h2>

                    <form method="POST" action="{{ route('admin.users.store') }}" class="flex flex-col gap-4">
                        @csrf

                        <div>
                            <label class="mb-1 block text-xs font-medium text-purple-400">Gebruikersnaam <span class="text-red-400">*</span></label>
                            <input type="text" name="username" value="{{ old('username') }}" required
                                   class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-sm text-white placeholder-purple-600 focus:border-purple-500 focus:outline-none @error('username') border-red-600 @enderror">
                            @error('username') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-purple-400">E-mail <span class="text-red-400">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-sm text-white placeholder-purple-600 focus:border-purple-500 focus:outline-none @error('email') border-red-600 @enderror">
                            @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-purple-400">Wachtwoord <span class="text-red-400">*</span></label>
                            <input type="password" name="password" required
                                   class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-sm text-white focus:border-purple-500 focus:outline-none @error('password') border-red-600 @enderror">
                            @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-purple-400">Wachtwoord bevestigen <span class="text-red-400">*</span></label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-sm text-white focus:border-purple-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-purple-400">Rol <span class="text-red-400">*</span></label>
                            <select name="role_id" required
                                    class="w-full rounded-lg border border-purple-800 bg-[#120d2a] px-3 py-2 text-sm text-white focus:border-purple-500 focus:outline-none @error('role_id') border-red-600 @enderror">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name === 'admin' ? 'Beheerder' : 'Speler' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="rounded-lg bg-purple-700 py-2 text-sm font-medium text-white hover:bg-purple-600 transition">
                            Account aanmaken
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
