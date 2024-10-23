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



@foreach($games as $game)
    <div>
        <h2>{{ $game->name }}</h2>
        <p>{{ $game->description }}</p>
        <p>Released in: {{ $game->year }}</p>

        <!-- Link naar de edit-pagina -->
        <a href="{{ route('games.edit', $game->id) }}">Edit</a>

        <!-- Delete-formulier -->
        <form action="{{ route('games.destroy', $game->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Weet je zeker dat je deze game wilt verwijderen?')">Delete</button>
        </form>
    </div>
@endforeach


@if(session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif
</body>
</html>
