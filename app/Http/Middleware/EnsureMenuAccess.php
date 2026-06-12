<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureMenuAccess
{
    public function handle(Request $request, Closure $next, ...$urls)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        abort_unless($user->canAccessAnyMenu($urls), 403);

        return $next($request);
    }
}
