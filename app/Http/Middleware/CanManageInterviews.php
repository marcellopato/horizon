<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanManageInterviews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->canManageInterviews()) {
            abort(403, 'Access denied. Only admins and reviewers can manage interviews.');
        }

        return $next($request);
    }
}
