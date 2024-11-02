<!-- resources/views/layouts/app.blade.php -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Your App Name')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<!-- Navigatiebalk -->
<!-- Navigatiebalk -->
<nav>
    <ul>
        <li><a href="{{ route('games.index') }}">Games</a></li>
        @auth
            <li><a href="{{ route('games.create') }}">Create Game</a></li>
            <li><a href="{{ route('profile.edit') }}">Mijn Profiel</a></li>
            @if(auth()->check() && auth()->user()->admin)
                <li><a href="{{ route('games.admin') }}">Admin Dashboard</a></li>
            @endif
        @else
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        @endauth
    </ul>

    <!-- Rechterkant van de navigatiebalk -->
    <div class="user-info">
        @auth
            <span>Ingelogd als: {{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @endauth
    </div>
</nav>


<div class="container">
    @yield('content')
</div>

<script src="{{ asset('js/app.js') }}"></script> <!-- Voeg hier je JS-bestand toe -->
</body>
</html>
