<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasRole('admin')) {
            abort(403, 'Bu sahifaga faqat administrator kira oladi.');
        }

        return $next($request);
    }
}
