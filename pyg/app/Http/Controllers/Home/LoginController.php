<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PygUser;
class LoginController extends Controller
{
    //
    public function login()
    {
        return view("home.login");
    }

    public function dologin(Request $request)
    {
        $pygUser=new PygUser();
        $params=$request->all();
        //dump($params);
        //不做参数检测
        $data=$pygUser->where("phone",$params["phone"])->first();
        if(! $data){
            return back()->with("error","用户名不存在")->withInput();
        }
        //dd($data);
        //验证密码
        if ($params["password"]!=$data->password){
            return back()->with("error","密码错误")->withInput();
        }
        //login保存session
        $request->session()->put("login",true);
        $request->session()->put("userInfo",[
            "id"=>$data->id,
            "username"=>$data->phone,
        ]);
//        $a=$request->session()->get("login");
//        $b=$request->session()->get("userInfo.id");
//        $c=$request->session()->get("userInfo.username");
//        dump($a,$b,$c);
        return redirect("/");
    }
    public function logout()
    {
        session([
            "login"=>false,
            "userInfo" =>null,
        ]);
        return redirect("/login")->with("error","退出成功!");
    }


    public function register()
    {
        return view("home.register");
    }
    //处理注册
    public function toregister(Request $request)
    {
        $pygUser=new PygUser();
        $params=$request->all();
        $params["username"]=$params["phone"];
        $phone=$pygUser->where("phone",$params["phone"])->first();
        if($phone){
            return back()->with("error","手机号存在")->withInput();
        }
        //数据不想验证了
        //插入数据
        $data=$pygUser->create($params);
        if (!$data){
           return back()->with("error","注册失败")->withInput();
        }
        return redirect("/login");
    }
    //随机生成数，当作验证码
    public function aa()
    {
        $a="123456789";
        $randStr = str_shuffle($a);//打乱字符串
        $code= substr($randStr,0,4);//substr(string,start,length);返回字符串的一部分
        return $code;
    }
}
