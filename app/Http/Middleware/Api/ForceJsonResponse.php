<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

/**
 * Force JSON Response Middleware
 *
 * @package \App\Http\Middleware\Api
 */
class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request                                                                          $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) : mixed
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
