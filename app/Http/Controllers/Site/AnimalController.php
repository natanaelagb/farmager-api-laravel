<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Logs;

class AnimalController extends Controller
{
    /** Display a listing of the resource. */
    public function index()
    {
        return response()->json([
            'animals' => Animal::all(),
        ]);
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('site\animals\create');
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $animal = Animal::create($request->all());
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' adicionou o animal '.$animal->id,
        ]);
        return $animal->load(['father', 'mother', 'events', 'products']);
    }

    /** Display the specified resource. */
    public function show(Animal $animal)
    {
        return $animal->load(['father', 'mother', 'events', 'products']);
    }

    public function filter(Request $request) {
        return ['animals' => Animal::where($request->campo, $request->busca)->get()];
    }

    /** Show the form for editing the specified resource. */
    public function edit(Animal $animal)
    {
        return $animal->load(['father', 'mother', 'events', 'products']);
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, Animal $animal)
    {
        if ($animal->update($request->all()) === false) {
            return response(
                "Couldn't update the animal with id {$animal->id}",
                Response::HTTP_BAD_REQUEST
            );
        }
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' alterou o animal '.$animal->id,
        ]);
        return $animal->load(['father', 'mother', 'events', 'products']);
    }

    /** Remove the specified resource from storage. */
    public function destroy(Animal $animal)
    {
        $animal->delete();
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' removeu o animal '.$animal->id,
        ]);
        return response()->json([
            'animals' => Animal::all(),
        ]);
    }
}
