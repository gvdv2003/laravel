<!-- resources/views/games/index.blade.php -->
@extends('layouts.games')  <!-- Gebruik de layout die je hebt gemaakt -->

@section('title', 'Game Index')  <!-- Voeg een titel toe voor de pagina -->

@section('content')  <!-- Dit is het content-gedeelte dat in de layout wordt weergegeven -->
<h1>Game Index</h1>

<!-- Zoekformulier -->
<form action="{{ route('games.index') }}" method="GET">
    <input type="text" name="search" placeholder="Zoek op naam" value="{{ request('search') }}">

    <select name="category_id">
        <option value="">Selecteer een categorie</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <button type="submit">Zoek</button>
</form>

<!-- Tabel met games -->
<table>
    <thead>
    <tr>
        <th>Naam</th>
        <th>Beschrijving</th>
        <th>Jaar</th>
        <th>Categorieën</th>
        <th>Creator</th>
        <th>Afbeelding</th>
        <th>Acties</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($games as $game)
        <tr>
            <td>{{ $game->name }}</td>
            <td>{{ $game->description }}</td>
            <td>{{ $game->year }}</td>
            <td>
                @foreach ($game->categories as $category)
                    {{ $category->name }}@if (!$loop->last), @endif
                @endforeach
            </td>
            <td>{{ $game->user->name }}</td>
            <td>
                @if($game->image_path)
                    <img src="{{ asset('storage/' . $game->image_path) }}" alt="Game Image" width="150">
                @endif
            </td>
            <td>
                <a href="{{ route('games.show', $game->id) }}">Bekijk</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
