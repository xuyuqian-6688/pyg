<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminApi\AuthLogics;
use App\Http\Controllers\AdminApi\BaseApi;
class AuthLogic
{
    public function handle($request, Closure $next)
    {
        $baseApi=new BaseApi();
        $authLogics=new AuthLogics();
        $res=$authLogics->check();
        if($res==1){
            return $next($request);
        }else{
            return  $baseApi->fail("没有权限！");
        }

    }
}