<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminApi\BaseApi;
use App\Model\PygAuth;
class AuthController extends Controller
{
    //权限接口
    public function index(Request $request)
    {
        $pygauth = new PygAuth();
        //接收参数 keyword type
        $params = $request->all();
        $where = [];
        if (!empty($params["keyword"])) {
            $where["auth_name"] = ["like", "%{$params['keyword']}%"];
            $data1=$pygauth->where('auth_name','like','%'.$params['keyword'].'%')
                ->get(['id','auth_name','pid_path','pid','auth_c','auth_a','is_nav','level'])->toArray();
            //dd($data);
        }
        //dd($where);
        //查询数据
        $data=$pygauth->where($where)->get(['id','auth_name','pid_path','pid','auth_c','auth_a','is_nav','level'])
            ->toArray();
        //print_r($data);
        //dd($data);

        //
        $BaseApi=new BaseApi();
        if (!empty($params['type']) && $params['type'] == 'tree') {
            //父子级分页
            $res=$BaseApi->get_tree_list($data);
        }else{
           //无限级分页
            $res=$BaseApi->get_cate_list($data1);
        }
        //dd($res);
        return $BaseApi->ok($res);
    }

    //显示指定的资源
    public function show($id){
        $pygauth = new PygAuth();
        $BaseApi=new BaseApi();
        $auth=$pygauth->find($id,['id','auth_name','pid_path','pid','auth_c','auth_a','is_nav','level']);
        return $BaseApi->ok($auth);
    }

    // 保存新建的资源
    public function store(Request $request)
    {
        $pygauth = new PygAuth();
        $BaseApi=new BaseApi();
        //接收数据
        $params=$request->all();
        //dd($params);
        //临时处理
        if(empty($params['pid'])){
            $params['pid'] = 0;
        }
        if(empty($params['is_nav'])){
            $params['is_nav'] = $params['radio'];
        }
        //dd($params['is_nav']);
        //dd($params["pid"]);
        //参数检测
//        $validate =$request->validate([
//            'auth_name' => 'required',
//            'pid|上级权限' => 'required',
//            'is_nav|菜单权限' => 'required',
//            //'auth_c|控制器名称' => '',
//            //'auth_a|方法名称' => '',
//        ],[
//            "require"=>"必选框不能为空！"
//        ]);
        $validate=$this->validate($request,
            [
                "auth_name"=>"required",
                "pid"=>"required",
                "is_nav"=>"required",
            ],
            [
                "required"=>":attribute 必须填写",
            ],
            [
                "auth_name"=>"菜单",
                "pid"=>"上级权限",
                "is_nav"=>"菜单权限"
            ]);
        //dd(2);
        if(!$validate == true){
            return $BaseApi->fail($validate, 401);
        }

//        if($params["auth_name"==null]){
//            return $BaseApi->fail("不能为空", 401);
//        }
//        if($params["pid"==null]){
//            return $BaseApi->fail("不能为空", 401);
//        }
//        if($params["pid"==null]){
//            return $BaseApi->fail("不能为空", 401);
//        }
//        dd(1);
        //添加数据（是否顶级，级别和pid_path处理）
        if($params["pid"]==0){
            $params["pid"]=0;
            $params["level"]=0;
            $params['pid_path'] = 0;
            $params['auth_c'] = '';
            $params['auth_a'] = '';
        }else{
            //如果不是顶级权限
            //查询上一级信息
            $p_data=$pygauth->find($params["pid"]);
            if(empty($p_data)){
                $BaseApi->fail("数据异常", 402);
            }
            //设置级别加1，家族族谱拼接
            $params["level"]=$p_data["level"]+1;
            $params['pid_path']=$p_data["pid_path"].'_'.$p_data["id"];
        }
        //插入数据
        //dd($params);
        $res=PygAuth::create($params);
        $info=$pygauth->find($res->id);
        //返回数据
        return $BaseApi->ok($info);

    }

    //保存更新的资源
    public function update(Request $request,$id){
        $pygauth = new PygAuth();
        $BaseApi=new BaseApi();
        //接收数据
        $params=$request->all();
        //dd($params);
        //临时处理
        if(empty($params['pid'])){
            $params['pid'] = 0;
        }
        if(empty($params['is_nav'])){
            $params['is_nav'] = $params['radio'];
        }
        $validate=$this->validate($request,
            [
                "auth_name"=>"required",
                "pid"=>"required",
                "is_nav"=>"required",
            ],
            [
                "required"=>":attribute 必须填写",
            ],
            [
                "auth_name"=>"菜单",
                "pid"=>"上级权限",
                "is_nav"=>"菜单权限"
            ]);
        //dd(2);
        if(!$validate == true){
            return $BaseApi->fail($validate, 401);
        }
        //查询需要修改的数据
        $auth=$pygauth->find($id);
        //dd($auth);
        if(!$auth){
            return $BaseApi->fail("数据异常",402);
        }
        //判断是否是顶级权限
        if($params["pid"]==0){
            $params["level"]=0;
            $params["pid_path"]=0;
        }else if ($params['pid'] != $auth['pid']){
            //如果修改其上级权限pid  重新设置level级别 和 pid_path 家族图谱
            $p_auth =$pygauth->find($params["pid"]);
            if(!$p_auth){
                return $BaseApi->fail("数据异常",402);
            }
            $params['level'] = $p_auth['level'] + 1;
            $params['pid_path'] = $p_auth['pid_path'] . '_' . $p_auth['id'];
        }
        //保存修改数据
        $p_auth->fill($params);
        $p_auth->save();
        //返回数据
        $info=$pygauth->find($id);
        return $BaseApi->ok($info);

    }

    //删除指定资源
    public function destroy(Request $request,$id)
    {
        $pygauth = new PygAuth();
        $BaseApi=new BaseApi();
        //判断是否有删除的是父级
        //dd(111);
        $total=$pygauth->where("pid",$id)->count();
        //dd($total);
        if($total>0){
            return $BaseApi->fail("该删除的有子权限，无法删除");
        }
        //dd(111);
        $res=$pygauth->find($id);
        if(!$res){
            return $BaseApi->fail("哥哥别瞎搞");
        }
        $a=$res->delete();
        if ($a){
            return $BaseApi->ok();
        }else{
            return $BaseApi->fail("删除失败");
        }
        //dd($a);

    }

}