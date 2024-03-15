<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

/**
 * API Key Authenticate Middleware
 *
 * @package \App\Http\Middleware\Api
 */
class ApiKeyAuthenticate
{
    /**
     * ApiKeyAuthenticate constructor.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param \Illuminate\Http\Request                                                                          $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next) : mixed
    {
        $this->authenticate($request);

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws AuthenticationException
     */
    protected function authenticate(Request $request) : void
    {
        $apiKey = $request->header('X-API-KEY');

        if ($apiKey === config('app.api_key')) {
            return;
        }

        $this->unauthenticated($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws AuthenticationException
     */
    protected function unauthenticated(Request $request) : void
    {
        throw new AuthenticationException(__('auth.api_key'));
    }
}
