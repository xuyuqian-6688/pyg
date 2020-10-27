<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class EnableCrossRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        $allow_origin = [
            'http://127.0.0.1:8080',//允许访问
        ];
        if (in_array($origin, $allow_origin)) {
            header("Access-Control-Allow-Origin:*");
            header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN');
            header('Access-Control-Expose-Headers', 'Authorization, authenticated');
            header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
            header('Access-Control-Allow-Credentials', 'true');
        }
        return $response;
    }
}
