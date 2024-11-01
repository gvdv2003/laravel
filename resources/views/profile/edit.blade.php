@extends('layouts.games')

@section('content')
    <div class="container">
        <h2>Profiel Bijwerken</h2>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="name" class="form-label">Naam</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Wachtwoord (laat leeg om niet te wijzigen)</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Bevestig Wachtwoord</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Bijwerken</button>
        </form>
    </div>

    <div class="container">
    <h3 class="mt-5">Jouw Games</h3>
    <table class="table mt-3">
        <thead>
        <tr>
            <th>Naam</th>
            <th>Jaar</th>
            <th>Categorieën</th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
        @foreach (auth()->user()->games as $game)
            <tr>
                <td>{{ $game->name }}</td>
                <td>{{ $game->year }}</td>
                <td>
                    @foreach ($game->categories as $category)
                        {{ $category->name }}@if (!$loop->last), @endif
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('games.edit', $game->id) }}" class="btn btn-warning btn-sm">Bewerken</a>
                    <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Weet je zeker dat je deze game wilt verwijderen?')">Verwijderen</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
@endsection
