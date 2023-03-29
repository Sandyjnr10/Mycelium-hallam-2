<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    
</head>
<!DOCTYPE html>

<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>



<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<body>

<!-- Sidebar -->
<div class="w3-sidebar w3-bar-block w3-border-right w3-black
" style="display:none" id="mySidebar">
  <button onclick="w3_close()" class="w3-bar-item">Close &times;</button>
  
  <a href="dashboard" class="w3-bar-item w3-button">Dashboard</a>
  <a href="#" class="w3-bar-item w3-button">Social Controller</a>
  <a href="#" class="w3-bar-item w3-button">Account</a>
  <a href="#" class="w3-bar-item w3-button">Scrape Results</a>
  <a href="#" class="w3-bar-item w3-button">Bot-Log</a>
</div>

<!-- Page Content -->
<div class="">
  <button class="w3-button w3-xlarge" onclick="w3_open()">☰</button>
</div>
<script>
function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
}

function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
}

function toggleSidebar() {
  $('.mySidebar').toggleClass('visible');
  $('.App').toggleClass('pushed');
}

</script>
     
</body>
</html> 

<body class="font-sans antialiased" style="background-color:#fbf8f0; padding-bottom:175px; padding-top:100px;">
    <header class="container fixed-top pt-4 ps-4 pe-4" style="background-color:#fbf8f0;">
        <div class="row pb-4 border-bottom">
            <div class="col-7 col-lg-4">
                <a href="/"><img src="/assets/images/motif.png" width="35" class="me-2" /><img src="/assets/images/mycelium-open-source-carbon-network-logo.png" width="144" /></a>
            </div>
            <div class="col-5 col-lg-8 text-end">
                @if (Route::has('login'))
                    <div class="">
                        @auth
                            {{-- <a href="{{ url('/dashboard') }}" class="text-muted">Dashboard</a> --}}
                        @else
                            <a href="{{ route('login') }}" class="text-muted">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ms-4 text-muted">Register</a>
                        @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </header>
    <main class="container p-4">
        @isset ($header)
        <div class="row">
            {{ $header}}
        </div>
        @endisset
        <div class="row pb-4">
            <x-jet-banner />
        </div>
        {{ $slot }}
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
    </body>
    </html>
    <!DOCTYPE html>


    
<head>
<style>
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  width: 60px;
} 


</style>
</head>
<body>

</html>

