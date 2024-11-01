@extends('layouts.games')  <!-- Gebruik de layout die je hebt gemaakt -->

@section('title', 'Game Create')  <!-- Voeg een titel toe voor de pagina -->

@section('content')  <!-- Dit is het content-gedeelte dat in de layout wordt weergegeven -->
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
@endsection
