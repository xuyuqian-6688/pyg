<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminApi\BaseApi;
use Illuminate\Support\Facades\Cache;
use App\Model\PygRole;
use App\Model\PygAuth;

class RoleController extends Controller
{
    //展示所有角色
    public function index(){
        $pygrole=new PygRole();
        $pygauth=new PygAuth();
        $BaseApi=new BaseApi();

        //查询所有
        $list=$pygrole->get()->toArray();
        //对每条数据id，对应的权限
        foreach ($list as $k =>$v){
            $id=$v['role_auth_ids'];
            //dump($id);
            $id=explode(',',$id);
            //dump($id);
            $auth=$pygauth->whereIn("id",$id)->get()->toArray();
            //dump($auth);
            $auth=$BaseApi->get_tree_list($auth);
            //dump($auth);
            $list[$k]['role_auths']=$auth;
        }
        //dd($list);
        unset($v);
        return $BaseApi->ok($list);
    }

    //角色详情
    public function show($id){
        $pygrole=new PygRole();
        $BaseApi=new BaseApi();
        //查找数据
        $data=$pygrole->find($id);
        if(!$data){
            return $BaseApi->fail("哥哥别瞎搞！");
        }
        //dd($data);
       return $BaseApi->ok($data);
    }

    //角色新增
    public function store(Request $request){
        $pygrole=new PygRole();
        $BaseApi=new BaseApi();
        //获取数据
        $params=$request->all();
        //dd($params);
        //数据验证
       /* $validate=$this->validate($request,
            [
                "role_name"=>"required",
                "role_auth_ids"=>"required",
            ],
            [
                "required"=>":attribute 必须填写",
            ],
            [
                "role_name"=>"角色",
                "role_auth_ids"=>"权限",
            ]);
        if(!$validate == true){
            return $BaseApi->fail($validate, 401);
        }*/
        //
        //dd($params);
        $params['role_auth_ids'] = $params['auth_ids'];
        $res=$pygrole->create($params);
        //dd($res);
        if(!$res){
            return $BaseApi->fail("哥哥你别瞎搞");
        }
        $info=$pygrole->find($res->id);
        return $BaseApi->ok($info);

    }

    //修改资源
    public function update(Request $request ,$id)
    {
        $pygrole=new PygRole();
        $BaseApi=new BaseApi();
        //获取数据
        $params=$request->all();
        $params['role_auth_ids'] = $params['auth_ids'];
        //修改
        $info=$pygrole->find($id);
        //dd($info);
        $info->fill($params);
        $res=$info->save();
        //dd($info->id);
        if(!$res){
            return $BaseApi->fail("哥哥你别瞎搞");
        }
        $info=$pygrole->find($info->id);
        return $BaseApi->ok($info);
    }

    //删除角色
    public function destroy(Request $request,$id)
    {
        $pygrole=new PygRole();
        $BaseApi=new BaseApi();
        //
        $res=$pygrole->find($id);
        if(!$res){
            return $BaseApi->fail("哥哥别瞎搞");
        }
        $a=$res->delete();
        if ($a){
            return $BaseApi->ok("成功！");
        }else{
            return $BaseApi->fail("删除失败");
        }
    }
}