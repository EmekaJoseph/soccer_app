<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sub-users can only run matches, results and live scores for their owner's
 * tournaments; everything else (tournaments, teams, players, predictions,
 * feedback, user management) is reserved for the owning admin account.
 */
class EnsureAccountIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            return response()->json(['message' => 'Only the tournament owner can do this.'], 403);
        }

        return $next($request);
    }
}
