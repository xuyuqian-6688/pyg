<?php

namespace App\Http\Controllers\Admin;
use App\Model\PygCategory;
use App\Http\Controllers\AdminApi\BaseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    //商品分类展示
    public function index(Request $request)
    {
        $pygCategory=new PygCategory();
        $baseApi=new BaseApi();

        //接收pid参数   接收type参数
        //$pid=$request->only("pid");
        $params=$request->all();
        $where=[];
        if(isset($params["pid"])){
            $where["pid"]=$params["pid"];
        }

        //查询所有数据，无限极分类展示
        $date=$pygCategory->where($where)->get()->toArray();
//        if(isset($params["type"])&&$params["type"]=="list"){
//            //返回普通列表
//            return $baseApi->ok($date);
//        }else{
//            //无限极分类，三级联动
//            $res=$baseApi->get_cate_list($date);
//            //dd($res);
//            return $baseApi->ok($res);
//        }
        if(!isset($params["type"]) || $params["type"]!="list"){
            //无限极分类，三级联动
            $res=$baseApi->get_cate_list($date);
           // dd(111);
            cache::put("category",$res,36000);
            if(cache::has("category")){
                return $baseApi->ok(cache::get("category"));
            }else{
                return $baseApi->ok($res);
            }
        }
        return $baseApi->ok($date);
    }

    //显示单个详情
    public function show($id)
    {
        $pygCategory=new PygCategory();
        $baseApi=new BaseApi();

        //查询
        $data=$pygCategory->find($id);

        //返回
        return $baseApi->ok($data);
    }

    //商品新增接口
    public function store(Request $request){
        $pygCategory=new PygCategory();
        $baseApi=new BaseApi();
        //获取数据
        $params=$request->all();
        //验证
        if($params["cate_name"]==''||$params["pid"]==''||$params["is_show"]==''||$params["is_hot"]||$params["sort"]==''){
            return $baseApi->fail("哥哥你别瞎搞，参数必须填！");
        }
        //dd($params);
        //判断是否是顶级
        if($params["pid"]==0){
            $params["pid_path"]=0;
            $params["pid_path_name"]='';
            $params["level"]=0;
        }else{
            $p_info=$pygCategory->where("id",$params["pid"])->first()->toArray();
            //dd($p_info);
            if(!$p_info){
                return $baseApi->fail("数据异常！");
            }
            $params["pid_path"]=$p_info["pid_path"].'_'.$p_info["id"];
            //dd($params["pid_path"]);
            $params["pid_path_name"]=$p_info["pid_path_name"].'_'.$p_info["cate_name"];
            $params["level"]=$p_info["level"]+1;
        }
        //dd($params);
        //返回数据
        if(isset($params["logo"])||empty($params["logo"])){
            $params["logo"]="暂无图片!";
        }
        $params["image_url"]=$params["logo"];
        $res=$pygCategory->create($params);
        if(!$res){
            return $baseApi->fail("数据异常！");
        }
        $res1=$pygCategory->find($res->id);
        return $baseApi->ok($res1);
    }

    //商品分类修改
    public function update(Request $request,$id){
        $pygCategory=new PygCategory();
        $baseApi=new BaseApi();

        //接收参数
        $params=$request->all();
        //参数验证
        if($params["cate_name"]==''||$params["pid"]==''||$params["is_show"]==''||$params["is_hot"]||$params["sort"]==''){
            return $baseApi->fail("哥哥你别瞎搞，参数必须填！");
        }
        //判断是否是顶级id
        if($params["pid"]==0){
            $params["pid_path"]=0;
            $params["pid_path_name"]='';
            $params["level"]=0;
        }else{
            $p_info=$pygCategory->where("id",$params["pid"])->first()->toArray();
            //dd($p_info);
            if(!$p_info){
                return $baseApi->fail("数据异常！");
            }
            $params["pid_path"]=$p_info["pid_path"].'_'.$p_info["id"];
            //dd($params["pid_path"]);
            $params["pid_path_name"]=$p_info["pid_path_name"].'_'.$p_info["cate_name"];
            $params["level"]=$p_info["level"]+1;
        }
        //判断图片合法性
        if(isset($params["logo"]) && empty($params["logo"])){
            $params["image_url"]=$params["logo"];
        }

        //修改数据
        $res=$pygCategory->where("id",$id)->update($params);
        if(!$res){
            return $baseApi->fail("数据异常！");
        }
        $info=$pygCategory->find($id);
        return $baseApi->ok($info);
    }

    //删除操作
    public function destroy($id){
        $pygCategory=new PygCategory();
        $baseApi=new BaseApi();

        //查询是否是父级列表
        $cate=$pygCategory->where("pid",$id)->count();
        if($cate>0){
            return $baseApi->fail("有子分类，不能删除！");
        }
        $pygCategory->where("id",$id)->delete();
        return $baseApi->ok("删除成功！");
    }
}