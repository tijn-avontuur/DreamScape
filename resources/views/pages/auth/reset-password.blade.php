<x-layouts::auth :title="__('Wachtwoord opnieuw instellen')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Wachtwoord opnieuw instellen')" :description="__('Vul hieronder je nieuwe wachtwoord in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                :label="__('E-mailadres')"
                type="email"
                required
                autocomplete="email"
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

            <!-- Confirm Password -->
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
                <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button">
                    {{ __('Wachtwoord opnieuw instellen') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
