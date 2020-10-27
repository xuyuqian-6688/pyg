<?php

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Model\PygCategory;
use App\Model\PygBrand;
use App\Model\PygSpecValue;

    Route::get("abc",function (){
        $c=new PygCategory();
        $bb=new PygBrand();
        //dump($c->get());
//       return $res=$bb->with("PygCategory")->find(1);
//        $a=$res->PygCategory->id;
//        return $a;
//        foreach ($a as $k => $v)
//        {
//            echo $v."<br>";
//        }
        $res=$c->find(72);
        $a=$res->brands;
       foreach ($a as $k =>$v){
           return $v->name;
       }
        /**
         * 一对多
         */

    });

    Route::get("q",function (){
        $p=new PygSpecValue();
        $arr=[
            'type_name' => '111',
            'spec' => [
                ['name' => '11111', 'sort' => 50, 'value'=>['黑色', '白色', '金色']],
                ['name' => '111111', 'sort' => 50, 'value'=>['64G', '128G', '256G']],
            ],
            'attr' => [
                ['name' => '1111', 'sort'=>50, 'value' => []],
                ['name' => '1111', 'sort'=>50, 'value' => ['进口', '国产']],
            ]
        ];
       $res=json_encode($arr);
        dd($res);
    });

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//use Illuminate\Support\Facades\DB;
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get("/",function (){
    dd(DB::table("pyg_admin")->find(1));

    //return md5("123456");
});
//测试
Route::get("aa",function (){
   $a=new \App\Http\Controllers\AdminApi\BaseApi();
   $data=["id"=>1];
   return $a->ok($data);
});

/**
 * 测试token_jwt
 */

//Route::post('/login','AuthController@login');//登录
//Route::group(['middleware' => 'api.auth'], function () {
//    Route::post('/home','AuthController@home');//获取用户信息
//    //Route::post('/logout','AuthController@logout');//退出
//});

Route::get("token",function (){
    $username = "13";
    $user_mes = User::where('username','=',$username)->first();
    dump($user_mes);
    $token=JWTAuth::fromUser($user_mes);
    dump($token);
    $res=JWTAuth::user($token);
    dump($res);
});

Route::get("cs","AdminApi\AuthLogics@check");
/**
 * 登录
 */

Route::middleware('cors')->group(function () {
    ////验证码路由
    Route::get("captcha","LoginController@captcha");
    //登录
    Route::post("logins","LoginController@logins");
    //退出
    Route::get("logout","LoginController@logout");

    //文件(单图片)上传
    Route::post("logo","AdminApi\AuthLogics@logo");
    //多图片上传
    Route::post("images","AdminApi\AuthLogics@images");

    /*//权限接口
    Route::resource("auths","Admin\AuthController",[],["id"=>"\d+"]);
    //菜单权限的接口
    Route::get("nav","Admin\NavController@nav");
    //角色路由
    Route::resource("roles","Admin\RoleController",[],["id"=>"\d+"]);
    //管理员路由
    Route::resource("admins","Admin\AdminController",[],["id"=>"\d+"]);*/
});

Route::middleware('cors')->middleware('auths')->group(function () {

    //权限接口
    Route::resource("auths","Admin\AuthController",[],["id"=>"\d+"]);
    //菜单权限的接口
    Route::get("nav","Admin\NavController@nav");
    //角色路由
    Route::resource("roles","Admin\RoleController",[],["id"=>"\d+"]);
    //管理员路由
    Route::resource("admins","Admin\AdminController",[],["id"=>"\d+"]);
    //商品的分类接口
    Route::resource("categorys","Admin\CategoryController",[],["id"=>"\d+"]);
    //商品品牌
    Route::resource("brands","Admin\BrandsController",[],["id"=>"\d+"]);
    //商品模型
    Route::resource("types","Admin\TypesController",[],["id"=>"\d+"]);
    //商品接口
    Route::resource("goods","Admin\GoodsController",[],["id"=>"\d+"]);
});
