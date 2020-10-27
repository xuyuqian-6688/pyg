<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminApi\BaseApi;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Model\PygAdmin;
use App\Model\PygAuth;
use App\Model\PygRole;
use Illuminate\Support\Facades\DB;

class NavController extends Controller
{
    //菜单权限
    public function nav()
    {
        $BaseApi=new BaseApi();
        $pygadmin=new PygAdmin();
        $pygAuth=new PygAuth();
        $pygrole=new PygRole();
        //获取用户id
        if(!cache::has("users")){
            return $BaseApi->fail("系统错误！",402);
        }
        $users=cache::get("users");
        //dd($users);
        //查询管理员的信息
        $info=$pygadmin->find($users[1]->id);
        //dd($info);
        $role_id=$info->role_id;
        //dd();
        if($role_id==1){
            //如果是1则是超级管理员，则给与全部权限
            $data=$pygAuth->where("is_nav",1)->get()->toArray();
           // dd($data);
        }else{
            //查询角色信息
            $role=$pygrole->find($role_id);
            //dd($role);
            $role_auth_ids=$role->role_auth_ids;
            //转化为数组
            //dd($role_auth_ids);
            $id=[];
            $id[]=explode(',',$role_auth_ids);
            //dd($id[0]);
            $data=$pygAuth->where("is_nav",'=',1)->whereIn('id',$id[0])->get()->toArray();
            //$data=DB::table('pyg_auth')->where(['is_nav'=>1],['id'=>$role_auth_ids])->select();
            //dd($data);
        }
        //调用父子级的树状结构
        $data=$BaseApi->get_tree_list($data);
        return $BaseApi->ok($data);

    }
}