<?php


namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReviewController extends Controller
{

    public function show($id)
    {
        $game = Game::with(['categories', 'reviews.user'])->findOrFail($id);

        \Log::info('Game data:', $game->toArray()); // Log de game data

        return view('games.show', compact('game'));
    }


    public function store(Request $request, $gameId)
    {
        $user = auth()->user();

        // Controleer of de gebruiker is ingelogd op minimaal 5 verschillende dagen
        $loginDays = collect(json_decode($user->login_days ?? '[]'));
        if ($loginDays->count() < 5) {
            return redirect()->back()->with('error', 'Je moet op 5 verschillende dagen zijn ingelogd om een review te kunnen schrijven.');
        }

        // Valideer de review
        $validated = $request->validate([
            'review_text' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5'
        ]);

        // Sla de review op
        Review::create([
            'user_id' => $user->id,
            'game_id' => $gameId,
            'review_text' => $validated['review_text'],
            'rating' => $validated['rating']
        ]);

        return redirect()->route('games.show', $gameId)->with('success', 'Review succesvol toegevoegd!');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Controleer of de gebruiker admin is
        if (!auth()->user()->admin) {
            return redirect()->route('games.index')->with('error', 'Je bent niet bevoegd om deze review te verwijderen.');
        }

        $review->delete();

        return redirect()->route('games.show', $review->game_id)->with('success', 'Review succesvol verwijderd!');
    }
}
