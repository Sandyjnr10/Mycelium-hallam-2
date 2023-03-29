<x-app-layout>
    <x-slot name="header">
        <h2 class="font-weight-bold">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <div>
        @livewire('api.api-token-manager')
    </div>
</x-app-layout>