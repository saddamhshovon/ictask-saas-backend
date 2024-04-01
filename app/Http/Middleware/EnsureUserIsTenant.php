<?php

namespace App\Http\Middleware;

use App\Enums\User\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role === Role::Tenant->value) {
            return $next($request);
        }

        return response()->json(
            [
                'message' => 'You are not allowed to do this operation.',
            ],
            \Illuminate\Http\Response::HTTP_FORBIDDEN
        );
    }
}
