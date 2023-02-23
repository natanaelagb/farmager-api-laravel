<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\AnimalEvent;
use App\Models\Logs;

class AnimalEventController extends Controller
{

    public function lastEvents() {
        return ['events'=>AnimalEvent::with(['user'])->orderBy('created_at', 'desc')->limit(15)->get()];
    }
    /** Display a listing of the resource. */
    public function index(Animal $animal)
    {
        return response()->json([
            'animal' => $animal,
            'events' => $animal->events()->with(['user'])->get(),
        ]);
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('site\animals\events\create');
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request, Animal $animal)
    {
        $request->request->add(['user_id' => Auth::id()]);
        $event = $animal->events()->create($request->all());
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' adicionou o evento '.$event->id.' do animal '.$animal->id,
        ]);
        return $event->load(['animal', 'user']);
    }

    /** Display the specified resource. */
    public function show(Animal $animal, AnimalEvent $event)
    {
        return $event->load(['animal', 'user']);
    }

    public function filter(Request $request) {
        return ['events' => AnimalEvent::where($request->campo, $request->busca)->with(['user'])->get()];
    }

    /** Show the form for editing the specified resource. */
    public function edit(Animal $animal, AnimalEvent $event)
    {
        return view('site\animals\events\edit', [
            'animal' => $animal,
            'event' => $event,
        ]);
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, Animal $animal, AnimalEvent $event)
    {
        if ($event->update($request->all()) === false) {
            return response(
                "Couldn't update the animal event with id {$event->id}",
                Response::HTTP_BAD_REQUEST
            );
        }
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' alterou o evento '.$event->id.' do animal '.$animal->id,
        ]);
        return $event->load(['animal', 'user']);
    }

    /** Remove the specified resource from storage. */
    public function destroy(Animal $animal, AnimalEvent $event)
    {
        $event->delete();
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' removeu o evento '.$event->id.' do animal '.$animal->id,
        ]);
        return response()->json([
            'animal' => $animal,
            'events' => $animal->events()->with(['user'])->get(),
        ]);
    }
}
