<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<!-- resources/views/games/index.blade.php -->

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

<table>
    <thead>
    <tr>
        <th>Naam</th>
        <th>Beschrijving</th>
        <th>Jaar</th>
        <th>Categorieën</th>
        <th>afbeelding</th>
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
            <td>
                @if($game->image_path)
                    <img src="{{ asset('storage/' . $game->image_path) }}" alt="Game Image" width="150">
                @endif
            </td>
            <td>
                <a href="{{ route('games.show', $game->id) }}">Bekijk</a>
                <a href="{{ route('games.edit', $game->id) }}">Bewerk</a>
                <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Verwijder</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>


</body>
</html>
