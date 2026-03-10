<x-layouts::auth :title="__('Registreren')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Account aanmaken')" :description="__('Vul je gegevens in om je account aan te maken')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Username -->
            <flux:input
                name="username"
                :label="__('Gebruikersnaam')"
                :value="old('username')"
                type="text"
                required
                autofocus
                autocomplete="username"
                :placeholder="__('Kies een gebruikersnaam')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('E-mailadres')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@voorbeeld.nl"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Wachtwoord')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Wachtwoord')"
                viewable
            />

            <!-- Bevestig Wachtwoord -->
            <flux:input
                name="password_confirmation"
                :label="__('Bevestig wachtwoord')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Bevestig wachtwoord')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Account aanmaken') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Al een account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Inloggen') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
