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

        \Log::info('Game data:', $game->toArray());

        return view('games.show', compact('game'));
    }


    public function store(Request $request, $gameId)
    {
        $user = auth()->user();


        $loginDays = collect(json_decode($user->login_days ?? '[]'));
        if ($loginDays->count() < 5) {
            return redirect()->back()->with('error', 'Je moet op 5 verschillende dagen zijn ingelogd om een review te kunnen schrijven.');
        }


        $validated = $request->validate([
            'review_text' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5'
        ]);


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


        if (!auth()->user()->admin) {
            return redirect()->route('games.index')->with('error', 'Je bent niet bevoegd om deze review te verwijderen.');
        }

        $review->delete();

        return redirect()->route('games.show', $review->game_id)->with('success', 'Review succesvol verwijderd!');
    }
}
