<?php


namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class AccessControlAllowOrigin
{
    public function handle($request, Closure $next)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Authorization, Accept, X-Requested-With , yourHeaderFeild");
        header("Access-Control-Expose-Headers: *");

        return $next($request);
    }
}