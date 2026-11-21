<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasAnyRole(['admin', 'editor', 'blogger'])) {
            abort(403, 'Bu sahifaga faqat admin, editor yoki blogger kira oladi.');
        }

        return $next($request);
    }
}
