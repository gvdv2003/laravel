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
    <p>{{ $game->name }}</p>
    <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE') <!-- Laravel gebruikt deze methode voor een DELETE-verzoek -->
        <button type="submit" onclick="return confirm('Weet je zeker dat je deze game wilt verwijderen?')">Delete</button>
    </form>
@endforeach

@if(session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif
</body>
</html>
