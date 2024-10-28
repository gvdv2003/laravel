<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\Category;

class GameController extends Controller
{
    public function index(Request $request)
    {
        // Laad de gebruiker met de game
        $query = Game::with('user'); // Gebruik with om de relatie te laden

        // Zoekfunctie
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Categorie filter
        if ($request->has('category_id') && $request->input('category_id') != '') {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->where('categories.id', $request->input('category_id'));
            });
        }

        $games = $query->get(); // Haal de resultaten op

        $categories = Category::all(); // Alle categorieën voor de filter

        return view('games.index', compact('games', 'categories'));
    }



    public function create()
    {
        $categories = Category::all();
        return view('games.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'year' => 'required|max:5',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'categories' => 'array', // Verwacht een array van categorie-ID's
            'categories.*' => 'exists:categories,id' // Zorg ervoor dat elk id bestaat
        ]);

        $game = new Game();
        $game->name = $request->input('name');
        $game->description = $request->input('description');
        $game->year = $request->input('year');
        $game->created_by = auth()->id();

        if ($request->hasFile('image_path')) {
            $game->image_path = $request->file('image_path')->storePublicly('images', 'public');
        }

        $game->save();

        if ($request->has('categories')) {
            $game->categories()->sync($request->input('categories'));
        }

        return redirect()->route('games.index')->with('success', 'Game successfully created!');
    }

    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        // Verwijder de bijbehorende categorieën
        $game->categories()->detach();

        // Verwijder de game
        $game->delete();

        return redirect()->route('games.index')->with('success', 'Game successfully deleted!');
    }

    public function edit($id)
    {
        $game = Game::findOrFail($id);
        $categories = Category::all();
        return view('games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, Game $game)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'year' => 'required|max:5',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id' // Zorg ervoor dat elk id bestaat
        ]);

        $game->name = $request->input('name');
        $game->description = $request->input('description');
        $game->year = $request->input('year');

        if ($request->hasFile('image_path')) {
            if ($game->image_path) {
                \Storage::disk('public')->delete($game->image_path);
            }
            $game->image_path = $request->file('image_path')->store('images', 'public');
        }

        if ($request->filled('categories')) {
            $game->categories()->sync($request->input('categories'));
        } else {
            $game->categories()->detach(); // Verwijder categorieën als er geen zijn geselecteerd
        }

        $game->save();

        return redirect()->route('games.show', $game->id)->with('success', 'Game successfully updated!');
    }

    public function show($id)
    {
        // Haal de game op via het ID met de bijbehorende gebruiker
        $game = Game::with('user')->findOrFail($id);

        // Geef de game door aan de view
        return view('games.show', compact('game'));
    }

}
