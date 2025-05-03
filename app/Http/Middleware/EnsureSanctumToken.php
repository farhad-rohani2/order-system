<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSanctumToken
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !session('token')) {
            $token = Auth::user()->createToken('web-ui')->plainTextToken;
            session(['token' => $token]);
        }

        return $next($request);
    }
}
