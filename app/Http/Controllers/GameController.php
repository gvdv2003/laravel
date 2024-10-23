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
        ]);

        $game = new Game();
        $game->name = $request->input('name');
        $game->description = $request->input('description');
        $game->year = $request->input('year');
        $game->created_by = 1;
        $game->save();
        $games = Game::all();

        return view('games.index',  compact('games'));
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

}
