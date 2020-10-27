<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;//使用
use App\Http\Controllers\AdminApi\BaseApi;
use App\Model\PygAdmin;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
class LoginController extends Controller
{
    //图片验证码接口
    public $token;
    public function captcha(CaptchaBuilder $builder)
    {
        $builder->build();
//
        $url = $builder->inline();  //获取图形验证码的url
        session()->put('piccode', $builder->getPhrase());
        $uniqid=session()->get("piccode");
        $baseapi=new BaseApi();
        //返回数据
        $data=[
            "src"=>$url,
            "uniqid"=>$uniqid
        ];
        return $baseapi->ok($data);
    }

    //登录接口
    public function logins(Request $request)
    {
        $pygAdmin=new PygAdmin();
        //return $request;
        $baseapi = new BaseApi();
        //接收参数
        $params = $request->only("username","password","code","uniqid");
        //dd($params["username"]);
        //dd($pygAdmin->find(1));
        //$res=DB::table("pyg_admin")->where("username","=",$params['username'])->first();
        //dd($res);
        //校验验证码
        if ($params['code']!=$params['uniqid']){
            return $baseapi->fail("验证码错误", 402);
        }
        //return 111;
        //数据表验证
        $res=$pygAdmin::where("username","=",$params['username'])->first();
        //dd($res);
        if(!$res){
            return $baseapi->fail("用户名错误", 403);
        }

        $password=md5($params['password']);
        if($res->password!==$password){
            return $baseapi->fail("密码错误", 404);
        }
        //return 112;
        //生成token令牌
        $this->token=JWTAuth::fromUser($res);
        //return 112;
        cache::put("delete_token",$this->token,86400);
        $delete_token=cache::get("delete_token");
        cache::put("model",$res,86400);
        $model=cache::get("model");
        $arr=[$delete_token,$model];
        $users=cache::put("users",$arr,86400);
        //return 11;
        //return $token;
        //返回数据
        $data=[
            "token"=>$this->token,
            "user_id"=>$res->id,
            "username"=>$res->username,
            "nickname"=>$res->nickname,
            "email"=>$res->email
        ];

        return $baseapi->ok($data);

    }

    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        //记录token为退出
        //获取当前请求中的token
        //$res=JWTAuth::parseToken()->invalidate();
       // return $res;
        //return JWTAuth::parseToken()->invalidate();
        //JWTAuth::invalidate(JWTAuth::getToken('CG_TK'));
        $delete_token=[];
        //返回数据
        //return $this->captcha("delete_token");
        $tokens=$this->token;
       // return 1;
        $delete_token=cache("delete_token")?cache("delete_token"):[];
        if(!cache("delete_token")){
            $delete_token=[];
        }
        //return 1;
        $delete_token=cache("delete_token");
        //return 1;
       // return 1;
        cache("delete_token",$delete_token,86400);
        $baseapi = new BaseApi();
        return $baseapi->ok();



    }
}