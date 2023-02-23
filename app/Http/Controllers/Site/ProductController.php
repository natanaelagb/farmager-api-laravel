<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Product;
use App\Models\FinancialTransaction;
use App\Models\User;
use App\Models\Logs;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /** Display a listing of the resource. */
    public function index()
    {
        return response()->json([
            'products' => Product::with(['animal','production','transaction'])->get(),
        ]);
    }
    
    /** Display a listing of the resource. */
    public function inventory()
    {
        $inventory = [
            'Leite' => 0,
            'Carne' => 0,
            'Ovo' => 0,
            'Finance' => 0.0
        ];
        $products = Product::all(['id', 'description', 'amount']);
        $finance = FinancialTransaction::sum('value');

        foreach($products as $value) {
            $inventory[$value->description] = $value->amount;
        }
        $inventory['Finance'] = number_format($finance, 2);

        return ['inventory' => $inventory];
    }


    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('site\products\create');
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $product = Product::create($request->all());
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' adicionou o produto '.$product->id,
        ]);
        return $product->load('animal');
    }

    /** Display the specified resource. */
    public function show(Product $product)
    {
        return $product->load(['animal','product']);
    }

    /** Show the form for editing the specified resource. */
    public function edit(Product $product)
    {
        return view('site\products\edit', [
            'product' => $product,
        ]);
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, Product $product)
    {
        if ($product->update($request->all()) === false) {
            return response(
                "Couldn't update the produto with id {$product->id}",
                Response::HTTP_BAD_REQUEST
            );
        }
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' alterou o produto '.$product->id,
        ]);
        return $product->load('animal');
    }

    /** Remove the specified resource from storage. */
    public function destroy(Product $product)
    {
        $product->delete();
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' removeu o produto '.$product->id,
        ]);
        return response()->json([
            'products' => Product::with(['animal','product'])->get(),
        ]);
    }
}
