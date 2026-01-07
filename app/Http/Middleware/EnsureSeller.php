<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSeller
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['seller', 'admin'], true)) {
            abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
}
