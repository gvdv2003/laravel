<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\Category;

class GameController extends Controller
{
    public function index(Request $request)
    {

        $query = Game::where('visible', true)->with('user');

        if (auth()->check() && auth()->user()->admin) {
            $query = Game::with('user'); // Admins kunnen alle games zien
        }


        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }


        if ($request->has('category_id') && $request->input('category_id') != '') {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->where('categories.id', $request->input('category_id'));
            });
        }

        $games = $query->get();

        $categories = Category::all();

        return view('games.index', compact('games', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('games.create', compact('categories'));
    }

    public function store(Request $request)
    {

        \Log::info('Ontvangen request data:', $request->all());
        \Log::info('Validatie begint voor het aanmaken van een game');


        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id'
        ]);

        \Log::info('Validatie succesvol, data:', $validatedData);

        try {

            $user = auth()->user();
            if (!$user) {
                return redirect()->back()->with('error', 'Je moet ingelogd zijn om een game te maken.');
            }

            \Log::info('Game aanmaken door user', ['user_id' => $user->id, 'is_admin' => $user->admin]);


            $game = new Game();
            $game->name = $validatedData['name'];
            $game->description = $validatedData['description'];
            $game->year = $validatedData['year'];
            $game->created_by = $user->id;


            $game->visible = false;


            if ($request->hasFile('image_path')) {
                $game->image_path = $request->file('image_path')->store('images', 'public');
                \Log::info('Afbeeldingspad opgeslagen', ['image_path' => $game->image_path]);
            }


            if ($game->save()) {
                \Log::info('Game succesvol opgeslagen', ['game_id' => $game->id]);
            } else {
                \Log::error('Game kon niet worden opgeslagen', ['game' => $game]);
                return redirect()->back()->with('error', 'Er is een fout opgetreden bij het aanmaken van de game. Probeer het opnieuw.');
            }


            if ($request->has('categories')) {
                $game->categories()->sync($validatedData['categories']);
                \Log::info('Categorieën gekoppeld aan game', ['categories' => $validatedData['categories']]);
            }

            return redirect()->route('games.index')->with('success', 'Game succesvol aangemaakt!');
        } catch (\Exception $e) {

            \Log::error('Fout bij het aanmaken van de game', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Er is een fout opgetreden bij het aanmaken van de game: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $game = Game::findOrFail($id);


        if (auth()->id() !== (int)$game->created_by && !auth()->user()->admin) {
            return redirect()->route('games.index')->with('error', 'Je bent niet bevoegd om deze game te verwijderen.');
        }

        $game->categories()->detach();
        $game->delete();

        return redirect()->route('games.index')->with('success', 'Game succesvol verwijderd!');
    }

    public function edit($id)
    {
        $game = Game::findOrFail($id);

        \Log::info('Update-methode aangeroepen voor game:', ['game_id' => $game->id]);

        \Log::info('Ingelogde gebruiker:', ['user_id' => auth()->id()]);
        \Log::info('Game created by:', ['created_by' => $game->created_by]);

        if (auth()->id() !== (int)$game->created_by && !auth()->user()->admin) {
            return redirect()->route('games.index')->with('error', 'Je bent niet bevoegd om deze game bij te werken.');
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

        $game = Game::with(['user', 'reviews.user'])->findOrFail($id);

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
