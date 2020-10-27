<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminApi\BaseApi;
use App\Model\PygGoods;

class GoodsController extends Controller
{
    //首页展示
    public function index(Request $request){
        $pygGoods=new PygGoods();
        $baseApi=new BaseApi();
        //接收参数
        //$params=$request->all();
        //分页展示
        $page=$request->only("page");
        if($page==1||$page==null){
            $pages=1;
        }else{
            $pages=$page['page'];
        }
        //获取总页码数
        $total=$pygGoods->count('*'); //每一页展示总数量
        //总页数
        $addNum=ceil($total/10);
        //查询数据
        $goods=$pygGoods->with(["types","category","brand"])->paginate($total)->toArray();
        //dd($goods);
        foreach ($goods["data"] as $key =>&$value){
            $value["type_name"]=$value["types"]["type_name"];
            $value["cate_name"]=$value["category"]["cate_name"];
            $value["brand_name"]=$value["brand"]["name"];
        }
        //dd($goods);
        $data=["total"=>$total,"per_page"=>$total,"current_page"=>$goods["current_page"],"last_page"=>$addNum,"data"=>$goods["data"]];
        //返回数据
        return $baseApi->ok($data);

    }
}