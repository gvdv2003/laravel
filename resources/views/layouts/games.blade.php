<!-- resources/views/layouts/app.blade.php -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Your App Name')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Voeg hier je CSS-bestand toe -->
</head>
<body>

<!-- Navigatiebalk -->
<nav>
    <ul>
        <li><a href="{{ route('games.index') }}">Games</a></li>
        @auth
            <li><a href="{{ route('games.create') }}">Create Game</a></li>
            <li><a href="{{ route('profile.edit') }}">Mijn Profiel</a></li>
            <li><form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit">Logout</button>
            </form></li>
        @else
            <li><a href="{{ route('login') }}">Log in om een game aan te maken</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        @endauth

    </ul>
</nav>

<div class="container">
    @yield('content')
</div>

<script src="{{ asset('js/app.js') }}"></script> <!-- Voeg hier je JS-bestand toe -->
</body>
</html>
