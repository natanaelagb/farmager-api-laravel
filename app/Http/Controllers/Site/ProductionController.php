<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\Logs;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    /** Display a listing of the resource. */
    public function index()
    {
        return response()->json([
            'productions' => Production::with(['animal','product'])->get(),
        ]);
    }

    /** Display a listing of the resource limited. */
    public function limit()
    {
        return response()->json([
            'productions' => Production::with(['animal','product'])->orderBy('created_at', 'desc')->limit(5)->get(),
        ]);
    }


    /** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        $product->amount = ($product->amount + $request->amount);
        $product->save();

        $production = Production::create($request->all());
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' adicionou a produção '.$production,
        ]);
        return $production->load(['animal','product']);
    }

    /** Display the specified resource. */
    public function show(Production $production)
    {
        return $production->load('animal');
    }

    /** Show the form for editing the specified resource. */
    public function edit(Production $production)
    {
        return view('site\products\edit', [
            'production' => $production,
        ]);
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, Production $production)
    {
        $product = Product::findOrFail($request->product_id);

        $product->amount = ($product->amount - $production->amount + $request->amount);
        $product->save();

        if ($production->update($request->all()) === false) {
            return response(
                "Couldn't update the produto with id {$production->id}",
                Response::HTTP_BAD_REQUEST
            );
        }
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' alterou o produto '.$production
        ]);
        return $production->load('animal','product');
    }

    /** Remove the specified resource from storage. */
    public function destroy(Production $production)
    {
        $product = Product::findOrFail($production->product_id);

        $product->amount = ($product->amount - $production->amount);
        $product->save();

        $production->delete();
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' removeu a produção '.$production,
        ]);
        return response()->json([
            'productions' => Production::with(['animal','product'])->get(),
        ]);
    }
}
