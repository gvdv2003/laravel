@extends('layouts.games')

@section('content')
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Wachtwoord</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Inloggen</button>
        </form>

        <div class="mt-3">
            <a href="{{ route('password.request') }}">Wachtwoord vergeten?</a>
        </div>
        <div class="mt-2">
            <a href="{{ route('register') }}">Geen account? Registreer hier!</a>
        </div>
    </div>
@endsection
