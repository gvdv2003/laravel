@extends('layouts.games')


@section('content')
    <div class="container">
        <h2>Beheer alle games</h2>

        <table class="table">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Beschrijving</th>
                <th>Jaar</th>
                <th>aangemaakt door:</th>
                <th>Zichtbaarheid</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($games as $game)
                <tr>
                    <td>{{ $game->name }}</td>
                    <td>{{ $game->description }}</td>
                    <td>{{ $game->year }}</td>
                    <td>{{ $game->user->name }}</td>
                    <td>
                        <form action="{{ route('admin.toggleVisibility', $game->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="checkbox" name="visible" onchange="this.form.submit()"
                                {{ $game->visible ? 'checked' : '' }}>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('games.edit', $game->id) }}" class="btn btn-warning btn-sm">Bewerken</a>
                        <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
