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

<!-- resources/views/games/edit.blade.php -->

<form action="{{ route('games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT') <!-- Methode om aan te geven dat het een PUT-verzoek is -->

    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $game->name) }}" required>
    </div>

    <div>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required>{{ old('description', $game->description) }}</textarea>
    </div>

    <div>
        <label for="year">Year:</label>
        <input type="text" id="year" name="year" value="{{ old('year', $game->year) }}" required>
    </div>

    @foreach ($categories as $category)
        <label>
            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                {{ in_array($category->id, old('categories', $game->categories->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
            {{ $category->name }}
        </label>
    @endforeach


    <div>
       <label for="image_path">Edit Image:</label>
       <input value="{{ old('image_path' , $game->image_path) }}" type="file" id="image_path" name="image_path">
   </div>

    <button type="submit">Update Game</button>
</form>



</body>
</html>
