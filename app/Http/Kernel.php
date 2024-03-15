<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http;

use App\Http\Middleware\Api\ForceJsonResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Router;

/**
 * HTTP Kernel
 *
 * @package \App\Http
 */
class Kernel extends HttpKernel
{
    /**
     * HTTP Kernel constructor.
     *
     * @param Application $app
     * @param Router      $router
     * @return void
     */
    public function __construct(Application $app, Router $router)
    {
        parent::__construct($app, $router);
        $this->prependToMiddlewarePriority(ForceJsonResponse::class);
    }

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\XssProtection::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            'json.response',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api_auth' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            'json.response',
            'auth:sanctum',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'accept_from_ips'       => \App\Http\Middleware\AcceptRequestOnlyFromIps::class,
        'accept_from_localhost' => \App\Http\Middleware\AcceptRequestOnlyFromLocalhost::class,
        'auth'                  => \App\Http\Middleware\Authenticate::class,
        'auth.api_key'          => \App\Http\Middleware\API\ApiKeyAuthenticate::class,
        'auth.basic'            => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers'         => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'                   => \Illuminate\Auth\Middleware\Authorize::class,
        'json.response'         => \App\Http\Middleware\Api\ForceJsonResponse::class,
        'guest'                 => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm'      => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed'                => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'              => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'              => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}
