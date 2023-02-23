<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logs;

class LogsController extends Controller
{
    /** Handle the incoming request. */
    public function __invoke(Request $request)
    {
        return response()->json([
            'logs' => Logs::with(['user'])->get(),
        ]);
    }
}
