<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;


class GameController extends Controller
{

    public function index(){

        $games = Game::all();

        return view('games.index', compact('games'));
    }
    public function create()
    {
        return view('games.create');
    }


    public function store(Request $request)
    {


        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'year' => 'required|max:5',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $game = new Game();
        $game->name = $request->input('name');
        $game->description = $request->input('description');
        $game->year = $request->input('year');
        $game->created_by = 1;

        $image_path = $request->file('image_path')->storePublicly('images', 'public');
        $game->image_path = $image_path;

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

        // Stuur de game naar de edit-view
        return view('games.edit', compact('game'));
    }

// Update de game met de nieuwe gegevens
    public function update(Request $request, $id)
    {
        // Valideer de input
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'year' => 'required|max:5',
        ]);

        // Haal de game op en werk deze bij
        $game = Game::findOrFail($id);
        $game->name = $request->input('name');
        $game->description = $request->input('description');
        $game->year = $request->input('year');
        $game->save();

        // Redirect terug naar de index-pagina met een succesbericht
        return redirect()->route('games.index')->with('success', 'Game successfully updated!');
    }


}
