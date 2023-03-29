<x-app-layout>
    <x-slot name="header">
        <h2 class="font-weight-bold">
            {{ __('Create Organisation') }}
        </h2>
    </x-slot>

    <div>
        @livewire('teams.create-team-form')
    </div>
</x-app-layout>