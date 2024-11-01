<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Nieuwe Game Aanmaken</title>
</head>
<body>
<h1>Nieuwe Game Aanmaken</h1>

<form action="{{ route('games.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div>
        <label for="name">Naam:</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div>
        <label for="description">Beschrijving:</label>
        <textarea id="description" name="description" required></textarea>
    </div>

    <div>
        <label for="year">Jaar:</label>
        <input type="number" id="year" name="year" required>
    </div>

    <div>
        <label>Categorieën:</label><br>
        @foreach ($categories as $category)
            <label>
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                    {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                {{ $category->name }}
            </label><br>
        @endforeach
    </div>

    <div>
        <label for="image_path">Upload Afbeelding:</label>
        <input type="file" id="image_path" name="image_path">
    </div>

    <button type="submit">Game Aanmaken</button>
</form>
</body>
</html>
