<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased" style="background-color:#fbf8f0; padding-bottom:175px;">
    <header class="container sticky-top pt-4 px-4" style="background-color:#fbf8f0">
        <div class="row pb-4">
            <div class="col-7 col-lg-4">
                <a href="/"><img src="/assets/images/motif.png" width="35" class="me-2" /><img src="/assets/images/mycelium-open-source-carbon-network-logo.png" width="144" /></a>
            </div>
            <div class="col-5 col-lg-8 text-end">
                @livewire('navigation-menu')

            </div>
        </div>
        <div class="row border-bottom border-top">
            <ul class="nav">
                <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-jet-nav-link>
                <x-jet-nav-link href="#" :active="request()->routeIs('data')">
                    {{ __('Data') }}
                </x-jet-nav-link>
            </ul>
        </div>
    </header>

    <main class="container p-4">
        <div class="row">
            {{ $header }}
        </div>
        <div class="row pb-4">
            <x-jet-banner />
        </div>
        <div class="row">
            <div class="row">
                {{ $slot }}
            </div>
        </div>
    </main>

    <footer class="container-fluid fixed-bottom p-4 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col col-lg-3"><img src="/assets/images/mycelium-logo-white.svg"
                        width="204" /><br />&copy; Mycelium Network Ltd 2023</div>
                <div class="col border-end border-2 border-white me-5">Talk to us: <a href="crew@mycelium-network.com"
                        class="text-white">crew@mycelium-network.com</a></div>
                <div class="col col-lg-2">
                    <ul>
                        <li><a href="/our-story" class="text-white">Our Story</a></li>
                        <li><a href="/research" class="text-white">Research</a></li>
                        <li><a href="/team" class="text-white">Team</a></li>
                    </ul>
                </div>
                <div class="col col-lg-2">
                    <ul>
                        <li><a href="/terms-and-conditions" class="text-white">Terms and conditions</a></li>
                        <li><a href="/privacy-policy" class="text-white">Privacy policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    @stack('modals')

    @livewireScripts
</body>

</html>
