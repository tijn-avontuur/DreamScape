<x-layouts::auth :title="__('E-mailverificatie')">
    <div class="mt-4 flex flex-col gap-6">
        <flux:text class="text-center">
            {{ __('Verifieer je e-mailadres door op de link te klikken die we je zojuist hebben gemaild.') }}
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
                {{ __('Een nieuwe verificatielink is verzonden naar het e-mailadres dat je bij registratie hebt opgegeven.') }}
            </flux:text>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Verificatiemail opnieuw verzenden') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer" data-test="logout-button">
                    {{ __('Uitloggen') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
