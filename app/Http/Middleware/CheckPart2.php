<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPart2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $part1, $part2): Response
    {
        if ($request->user()->part == $part1 || $request->user()->part == $part2) {
            return $next($request);
        }

        return redirect('/');
    }
}
