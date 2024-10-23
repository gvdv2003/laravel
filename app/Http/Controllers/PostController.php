<?php

namespace App\Http\Controllers;

use App\Models\game;
//use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Deze functie toont het create-formulier
    public function create()
    {
        return view('create');
    }

    // Deze functie slaat de gegevens op in de database
    public function store(Request $request)
    {
        // Validatie van de input
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'year' => 'required|max:4',
        ]);

        // Gegevens opslaan in de database
        game::create($validatedData);

        // Doorverwijzen naar een pagina, bijvoorbeeld een overzichtspagina
        return redirect()->route('home')->with('success', 'Post created successfully!');
    }
}
