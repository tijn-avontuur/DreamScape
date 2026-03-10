<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Weergave-instellingen')] class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Weergave-instellingen') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Weergave')" :subheading="__('Pas de weergave-instellingen van je account aan')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Licht') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Donker') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-pages::settings.layout>
</section>
