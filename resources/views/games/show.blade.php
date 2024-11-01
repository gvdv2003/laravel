<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('') }}">
    <title>Game Details</title>
</head>
<body>
<h1>{{ $game->name }}</h1>
<p><strong>Description:</strong> {{ $game->description }}</p>
<p><strong>Year:</strong> {{ $game->year }}</p>

<h3>Categories:</h3>
<ul>
    @foreach ($game->categories as $category)
        <li>{{ $category->name }}</li>
    @endforeach
</ul>


@if($game->image_path)
    <img src="{{ asset('storage/' . $game->image_path) }}" alt="{{ $game->name }}" width="200">
@endif

<p>Created by: {{ $game->user->name }}</p>

<a href="{{ route('games.index') }}">Back to all games</a>

@auth
    @if(auth()->id() == $game->created_by)
        <a href="{{ route('games.edit', $game->id) }}">Edit</a>
        <form action="{{ route('games.destroy', $game->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete this game?')">Delete Game</button>
        </form>
    @else
        {{-- Dit is om te debuggen --}}

    @endif
@endauth






</body>
</html>
