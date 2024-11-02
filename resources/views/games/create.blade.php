@extends('layouts.games')  <!-- Gebruik de layout die je hebt gemaakt -->

@section('title', 'Game Create')  <!-- Voeg een titel toe voor de pagina -->

@section('content')  <!-- Dit is het content-gedeelte dat in de layout wordt weergegeven -->
<h1>Nieuwe Game Aanmaken</h1>

<form id="game-form" action="{{ route('games.store') }}" method="POST" enctype="multipart/form-data">
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
        <input type="file" id="image_path" name="image_path" accept="image/*">
    </div>

    <button type="submit">Game Aanmaken</button>
</form>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<script>
    document.getElementById('game-form').addEventListener('submit', function(event) {
        const fileInput = document.getElementById('image_path');
        const file = fileInput.files[0];

        if (file) {
            // Controleer de bestandsgrootte (bijv. 2MB)
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxSize) {
                event.preventDefault(); // Voorkom dat het formulier wordt verzonden
                alert('De afbeelding is te groot. Maximaal toegestaan is 2MB.');
            }
        }
    });
</script>

@endsection
