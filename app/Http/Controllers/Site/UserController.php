<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Logs;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /** Display a listing of the resource. */
    public function index()
    {
        return response()->json([
            'users' => User::all(),
        ]);
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('site\users\create');
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request)
    {

        $post = $request->all();
        $post['password'] = Hash::make($request->password);
        $user = User::create($post);
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' adicionou o usuário '.$user->id,
        ]);
        return $user->load('logs');
    }

    /** Display the specified resource. */
    public function show(User $user)
    {
        return $user->load('logs');
    }

    public function filter(Request $request) {
        return ['users' => User::where($request->campo, $request->busca)->get()];
    }

    /** Show the form for editing the specified resource. */
    public function edit(User $user)
    {
        return view('site\users\edit', [
            'user' => $user,
        ]);
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, User $user)
    {
        // return $request->except(['password']);
        if ($user->update( $request->except(['password'])) === false) {
            return response(
                "Couldn't update the user with id {$user->id}",
                Response::HTTP_BAD_REQUEST
            );
        }
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' alterou o usuário '.$user->id,
        ]);
        return $user->load('logs');
    }

    /** Remove the specified resource from storage. */
    public function destroy(User $user)
    {
        $user->delete();
        Logs::create([
            'user_id' => Auth::id(),
            'description' => 'O usuário '.Auth::id().' removeu o usuário '.$user->id,
        ]);
        return response()->json([
            'users' => User::all(),
        ]);
    }
}
