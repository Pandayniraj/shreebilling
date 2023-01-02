<?php

namespace App\Http\Middleware;

use Closure;

class SetDBConnection
{
    public function handle($request, Closure $next)
    {
        if (session('database')) {
            config(['database.connections.mysql.database' => session('database')]);
            config(['database.default' => session('database')]);
        }

        return $next($request);
    }
}
