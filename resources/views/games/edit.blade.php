@extends('layouts.games')  <!-- Gebruik de layout die je hebt gemaakt -->

@section('title', 'game edit')  <!-- Voeg een titel toe voor de pagina -->

@section('content')  <!-- Dit is het content-gedeelte dat in de layout wordt weergegeven -->

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

@endsection
