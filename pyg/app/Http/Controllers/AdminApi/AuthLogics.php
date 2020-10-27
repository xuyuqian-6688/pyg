<?php


namespace App\Http\Controllers\AdminApi;
use Illuminate\Http\Request;
use App\Model\PygAdmin;
use App\Model\PygRole;
use App\Model\PygAuth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\AdminApi\BaseApi;
class AuthLogics
{
    public function check()
    {
        $baseApi=new BaseApi();
        $pygAdmin=new PygAdmin();
        $pygRole=new PygRole();
        $pygAuth=new PygAuth();
        $action = \Route::current()->getActionName();
        $a=explode("\\",$action);
        //dd($a);
        $b=$a[4];
        $c=explode("@",$b);
        //dd($c[1]);
        $d=$c[1];
        $as=[];
        $as[0]=$d;
        list($class, $method) = explode('@', $action);
        $controller = substr(strrchr($class, '\\'), 1);
        $cs=[];
        $cs[0]=$controller;
//        dump($as);
//        dd($cs);
        //return ['controller' => $controller, 'method' => $method];
        if($controller=="IndexController"&&$method=="index"){
            return 1;
        }
        //获取用户id
        //获取用户id
        if(!cache::has("users")){
            return $baseApi->fail("系统错误！",402);
        }
        $users=cache::get("users");
        //dd($users);
        //查询管理员的信息
        $info=$pygAdmin->find($users[1]->id);
        //dd($info);
        $role_id=$info->role_id;
        //dd($role_id);
        //判断是否是超级管理员
        if($role_id==1){
            return 1;
        }
        //dd(2);
        //查询管理员具有那些权限
        $role=$pygRole->find($role_id);
        //取出权限ids分割成数组
        $role_auth_ids=explode(",",$role->role_auth_ids);
        //获取当前访问的是哪个权限
        //dd($role_auth_ids);
        $auth=$pygAuth->whereIn("auth_c",$cs)->whereIn("auth_a",$as)->first();
        //dd($auth);
        $auth_id=$auth->id;
        //判断这个id是否在表中全限中
        if(in_array($auth_id,$role_auth_ids)){
            return 1;
        }
        return 0;
    }


    //文件（单图片）上传
    public function logo(Request $request)
    {
        $baseApi=new BaseApi();
        //接收参数
        $type=$request->only("type");
        if(empty($type)){
            return $baseApi->fail("缺少参数！");
        }
        //获取文件
        $file_image=$request->file("logo");
        if(empty($file_image)){
            return $baseApi->fail("缺少上传的文件");
        }
        //设置文件类型
        $allowed_extensions = ["png", "jpg", "gif","jpeg"];
        if(!in_array($file_image->getClientOriginalExtension(),$allowed_extensions)){
            return $baseApi->fail("文件类型 错误");
        }
        //图片移动
        $path="/uploads".'/'.$type;
        //获取文件的后缀
        $extension = $file_image->getClientOriginalExtension(); //.jpg
        //拼接文件名
        $file_name=md5(time()).'.'.$extension; //1234564564564.jpg
        //移动
        $res= $file_image->move($path,$file_name);
        if(!$res){
            return $baseApi->fail("系统错误");
        }
        $file_path=asset($path,$file_name);
        return $baseApi->ok($file_path);

    }

    //多图片上传
    public function images(Request $request)
    {
        $baseApi=new BaseApi();
        //获取数据
        $type=$request->only("type");
        if(empty($type)){
            $type=$request->only("goods");
        }
        //获取上传图片
        $files=$request->file("images");
        if(empty($files)){
            return $baseApi->fail("缺少上传的文件");
        }
        //设置文件类型
        $allowed_extensions = ["png", "jpg", "gif","jpeg"];
        //逐个上传文件
        $data=["success"=>[],"error"=>[]];
        foreach ($files as $file){
            if (!in_array($file->getClientOriginalExtension(),$allowed_extensions)){
                return $baseApi->fail("文件类型错误");
            }
            //图片移动地址
            $path="/uploads".'/'.$type;
            //获取文件的后缀
            $extension = $file->getClientOriginalExtension();
            //拼接文件名
            $file_name=md5(time()).'.'.$extension;
            //移动
            $res= $file->move($path,$file_name);
            if($res){
//                $file_path=asset($path,$file_name);
                $data["success"][]=asset($path,$file_name);
                //return $baseApi->ok($file_path);
            }else{
                $data["error"][]=[
                    "name"=>$file->getInfo("name"),
                    "msg"=>$file->getError(),
                ];
            }
        }
        return $baseApi->ok($data);
    }
}