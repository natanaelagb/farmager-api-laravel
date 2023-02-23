<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Logs;
use App\Models\User;
use App\Models\Product;
use App\Models\FinancialTransaction;

class FinancialTransactionController extends Controller
{
    /** Display a listing of the resource. */
    public function index()
    {
        return response()->json([
            'transactions' => FinancialTransaction::with(['user','product'])->get(),
        ]);
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('site\financial-transactions\create');
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if(($product->amount - $request->amount )>= 0) {
            $product->amount = $product->amount - $request->amount;
            $product->save();
        } else {
            return response("Quantidade vendida maior que a disponível em estoque", Response::HTTP_BAD_REQUEST);
        }


        $request->request->add(['value'=>($product->unit_price * $request->amount)]);

        $transaction = FinancialTransaction::create($request->all());
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' adicionou a transação financeira '.$transaction->id,
        ]);
        return $transaction->load(['user','product']);
    }

    /** Display the specified resource. */
    public function show(FinancialTransaction $transaction)
    {
        return $transaction->load('user');
    }

    /** Show the form for editing the specified resource. */
    public function edit(FinancialTransaction $transaction)
    {
        return view('site\financial-transactions\edit', [
            'transaction' => $transaction,
        ]);
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, FinancialTransaction $transaction)
    {

        $product = Product::findOrFail($request->product_id);

        if((($product->amount + $transaction->amount) - $request->amount )>= 0) {
            $product->amount = ($product->amount + $transaction->amount - $request->amount);
            $product->save();
        } else {
            return response("Quantidade vendida maior que a disponível em estoque", Response::HTTP_BAD_REQUEST);
        }

        $product->save();
        $request->request->remove('value');
        $request->request->add(['value'=>($product->unit_price * $request->amount)]);

        if ($transaction->update($request->all()) === false) {
            return response(
                "Couldn't update the financial transaction with id {$transaction->id}",
                Response::HTTP_BAD_REQUEST
            );
        }
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' alterou a transação financeira '.$transaction->id,
        ]);
        return $transaction->load(['user', 'product']);
    }

    /** Remove the specified resource from storage. */
    public function destroy(FinancialTransaction $transaction)
    {
        $product = Product::findOrFail($transaction->product_id);
        $product->amount = ($product->amount + $transaction->amount);
        $product->save();
        
        $transaction->delete();
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' removeu a transação financeira '.$transaction->id,
        ]);
        return response()->json([
            'transactions' => FinancialTransaction::with(['user','product'])->get(),
        ]);
    }
}
