@extends('layouts.games')

@section('title', 'Game Show')

@section('content')
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
        @endif
    @endauth

    <h2>Reviews</h2>
    @forelse ($game->reviews as $review)
        <div>

            @if($review->rating) <!-- Controleer of er een rating is -->
            <p><strong>Beoordeling:</strong> {{ $review->rating }} / 5</p>
            @endif
            <p>{{ $review->review_text }}</p>
            <small>Door {{ $review->user->name }} op {{ $review->created_at->format('d-m-Y') }}</small>

            @if (auth()->user()->admin)
                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Verwijder</button>
                </form>
            @endif
        </div>
    @empty
        <p>Er zijn nog geen reviews voor deze game.</p>
    @endforelse

    @if (auth()->check() && auth()->user()->login_days && count(json_decode(auth()->user()->login_days)) >= 5)
        <form action="{{ route('reviews.store', $game->id) }}" method="POST">
            @csrf
            <div>
                <label for="review_text">Schrijf een review:</label>
                <textarea id="review_text" name="review_text" required></textarea>
            </div>
            <div>
                <label for="rating">Beoordeling (optioneel):</label>
                <input type="number" id="rating" name="rating" min="1" max="5">
            </div>
            <button type="submit">Review Toevoegen</button>
        </form>
    @else
        <p>Je moet op 5 verschillende dagen zijn ingelogd om een review te kunnen schrijven.</p>
    @endif

@endsection
