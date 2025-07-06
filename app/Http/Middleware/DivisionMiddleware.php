<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisionMiddleware
{
    public function handle(Request $request, Closure $next, $divisionId)
    {
        if (!Auth::check() || Auth::user()->division_id != $divisionId) {
            return redirect()->route('head-of-division.dashboard')
                ->with('error', 'Tidak memiliki akses ke halaman tersebut');
        }

        return $next($request);
    }
}
