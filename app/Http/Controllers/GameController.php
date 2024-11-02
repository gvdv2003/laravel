<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\Category;

class GameController extends Controller
{
    public function index(Request $request)
    {
        // Laad alleen zichtbare games voor niet-admins
        $query = Game::where('visible', true)->with('user');

        if (auth()->check() && auth()->user()->admin) {
            $query = Game::with('user'); // Admins kunnen alle games zien
        }

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
            'categories' => 'array',
            'categories.*' => 'exists:categories,id'
        ]);

        $game = new Game($validatedData);
        $game->created_by = auth()->id();
        $game->visible = auth()->user()->admin ? true : false; // Standaard onzichtbaar als geen admin

        if ($request->hasFile('image_path')) {
            $game->image_path = $request->file('image_path')->store('images', 'public');
        }

        $game->save();

        if ($request->filled('categories')) {
            $game->categories()->sync($request->input('categories'));
        }

        return redirect()->route('games.index')->with('success', 'Game succesvol aangemaakt!');
    }

    public function destroy($id)
    {
        $game = Game::findOrFail($id);

        // Controleer of de ingelogde gebruiker de eigenaar is of admin
        if (auth()->id() !== $game->created_by && !auth()->user()->admin) {
            return redirect()->route('games.index')->with('error', 'Je bent niet bevoegd om deze game te verwijderen.');
        }

        $game->categories()->detach();
        $game->delete();

        return redirect()->route('games.index')->with('success', 'Game succesvol verwijderd!');
    }

    public function edit($id)
    {
        $game = Game::findOrFail($id);

        // Controleer of de ingelogde gebruiker de eigenaar is of admin
        if (auth()->id() !== $game->created_by && !auth()->user()->admin) {
            return redirect()->route('games.index')->with('error', 'Je bent niet bevoegd om deze game te bewerken.');
        }

        $categories = Category::all();
        return view('games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, Game $game)
    {
        if (auth()->id() !== $game->created_by && !auth()->user()->admin) {
            return redirect()->route('games.index')->with('error', 'Je bent niet bevoegd om deze game bij te werken.');
        }

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'year' => 'required|max:5',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id'
        ]);

        $game->update($validatedData);

        if ($request->hasFile('image_path')) {
            if ($game->image_path) {
                \Storage::disk('public')->delete($game->image_path);
            }
            $game->image_path = $request->file('image_path')->store('images', 'public');
        }

        $game->categories()->sync($request->input('categories', []));

        return redirect()->route('games.show', $game->id)->with('success', 'Game succesvol bijgewerkt!');
    }

    public function show($id)
    {
        $game = Game::with('user')->findOrFail($id);

        return view('games.show', compact('game'));
    }

    public function adminIndex()
    {
        $this->authorizeAdmin();

        $games = Game::all();
        return view('games.admin', compact('games'));
    }

    private function authorizeAdmin()
    {


        if (!auth()->check() || !auth()->user()->admin) {
            abort(403, 'Alleen admins hebben toegang.');
        }
    }


    public function toggleVisibility($id)
    {
        $this->authorizeAdmin();

        $game = Game::findOrFail($id);
        $game->visible = !$game->visible;
        $game->save();

        return redirect()->route('games.admin')->with('success', 'Zichtbaarheid aangepast!');
    }
}
