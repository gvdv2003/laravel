<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\Category;



class GameController extends Controller
{

    public function index(Request $request)
    {
        $query = Game::query();

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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'categories' => 'array', // Verwacht een array van categorie-ID's
        ]);

        $game = new Game();
        $game->name = $request->input('name');
        $game->description = $request->input('description');
        $game->year = $request->input('year');
        $game->created_by = 1;

        $image_path = $request->file('image_path')->storePublicly('images', 'public');
        $game->image_path = $image_path;

        if ($request->has('categories')) {
            $game->categories()->sync($request->input('categories'));
        }

        $game->save();

        return redirect()->route('games.index')->with('success', 'Game successfully created!');

    }

    public function destroy($id)
    {
        // Zoek de game op basis van het ID
        $game = Game::findOrFail($id);

        // Verwijder de game
        $game->delete();

        $games = Game::all();
        // Redirect terug naar de lijstpagina met een succesbericht
        return redirect()->route('games.index', compact('games'))->with('success', 'Game successfully deleted!');
    }

    // Edit pagina voor een specifieke game
    public function edit($id)
    {
        // Haal de game op met het gegeven ID
        $game = Game::findOrFail($id);

        $categories = Category::all();
        return view('games.edit', compact('game', 'categories'));
    }

// Update de game met de nieuwe gegevens
    public function update(Request $request, Game $game)
    {


        // Valideer de input
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'year' => 'required|max:5',
            'categories' => 'array',
        ]);

        // Haal de game op en werk deze bij
        $game->name = $request->input('name');
        $game->description = $request->input('description');
        $game->year = $request->input('year');
        if ($request->hasFile('image_path')) {
            // Verwijder de oude afbeelding, indien aanwezig
            if ($game->image_path) {
                \Storage::disk('public')->delete($game->image_path);
            }

            // Sla de nieuwe afbeelding op en update het pad
            $imagePath = $request->file('image_path')->store('images', 'public');
            $game->image_path = $imagePath;
        }
        if ($request->has('categories')) {
            $game->categories()->sync($request->input('categories'));
        }

        $game->save();



        // Redirect terug naar de index-pagina met een succesbericht
        return redirect()->route('games.show', $game->id)->with('success', 'Game successfully updated!');
    }
    public function show($id)
    {
        // Haal de game op via het ID
        $game = Game::findOrFail($id);

        // Geef de game door aan de view
        return view('games.show', compact('game'));
    }



}
