<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * jwt 测试
     */

    //登录
    public function login(Request $request){

        $username = "13";
        $password = "132456";
        $user_mes = User::where('username','=',$username)->first();
//        return $user_mes;
        if (!$user_mes || !Hash::check($password, $user_mes->password)) {
            return "账号或密码错误";
        }
        $token=JWTAuth::fromUser($user_mes);//生成token
        if (!$token) {
            return "登录失败，请重试";
        }
        return response()->json(['token'=>$token]);

    }

    //获取用户信息
    public function home(){
        $user=JWTAuth::parseToken()->touser();//获取用户信息
        return $user;

    }

    //退出
    public function logout(){
        JWTAuth::parseToken()->invalidate();//退出
        return '退出成功';
    }

}