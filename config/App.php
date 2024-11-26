<?php

namespace Config;

class App
{
    public static array $middlewareAliases = [
        'auth' => \App\Middleware\Authenticate::class,
        'role_supervisor' => \App\Middleware\RoleMiddleware::class,
        'role_admin' => \App\Middleware\RoleMiddleware::class
    ];
}
