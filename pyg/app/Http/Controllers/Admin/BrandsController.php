<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AdminApi\BaseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PygBrand;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager as Image;
use App\Model\PygGoods;
class BrandsController extends Controller
{
    public function index(Request $request)
    {
        $baseApi=new BaseApi();
        $pygBrand=new PygBrand();
        //接收参数cate_id,判断有还是没有
        $params=$request->all();
        $where=[];
        //查询分类下的所有品牌列表
        //->where($where)->get()->toArray();
//            $cate=DB::table("pyg_brand")->join("pyg_category t2","pyg_brand.cate_id=t2.id","left")
//                ->where($where)->get()->toArray();

        //dd($cate);
        if(isset($params["cate_id"])&&!empty($params["cate_id"])){
            $where["pyg_brand.cate_id"]=$params["cate_id"];
            //查询数据
            $cate=$pygBrand->where($where)
                ->leftJoin("pyg_category",function ($join){
                    $join->on("pyg_brand.cate_id","=","pyg_category.id");
                })//"pyg_brand.cate_id=pyg_category.id"
                ->get()
                ->toArray();
            dd($cate);
        }else{
            //分页展示
            $page=$request->only("page");
            if($page==1||$page==null){
                $pages=1;
            }else{
                $pages=$page['page'];
            }
            //获取总页码数
            $total=$pygBrand->count('*'); //10
            //查询
            $cate=$pygBrand ->leftJoin("pyg_category",function ($join){
                $join->on("pyg_brand.cate_id","=","pyg_category.id");
            })->paginate($total)->toArray();
            //dd($cate);

            //总页数
            $addNum=ceil($total/10);
            //拼接总数居
            $arr=['total'=>$total,'per_page'=>10,"current_page"=>$cate["current_page"],"last_page"=>$addNum
            ,"data"=>$cate["data"]];
            return $baseApi->ok($arr);
        }
    }

    //角色详情，用户修改弹窗，内容自动补全
    public function show(Request $request,$id){
        $baseApi=new BaseApi();
        $pygBrand=new PygBrand();
        //查询数据
        $res=$pygBrand->find($id);
        if(!$res){
            return $baseApi->fail("数据异常！");
        }
        //dd($res);
        return $baseApi->ok($res);
    }

    //商品品牌修改
    public function update(Request $request,$id)
    {
        $baseApi=new BaseApi();
        $pygBrand=new PygBrand();
        //获取请求参数
        $params=$request->all();
        //判断是否修改logo
        if(isset($params["logo"])&&!empty($params["logo"])&&is_file('.'.$params["logo"])){
            Image::make($params["logo"])->resize(200,200);
        }
        //数据验证
        $this->validate($request,[
           "name"=>'required',
           "cate_id"=>'required',
           "is_hot"=>'required',
           "sort"=>'required',
        ],[
            'required' => ':attribute 为必填项',
        ],[
            "name"=>"品牌名",
            "cate_id"=>"所属分类id",
            "is_hot"=>"是否热门  1是  0否",
            "sort"=>"排序"
        ]);

        //执行修改
        $res=$pygBrand->where("id",$id)->update($params);
        if(!$res){
            return $baseApi->fail("修改失败！");
        }
        $info=$pygBrand->find($id);
        return $baseApi->ok($info);
    }

    //商品品牌新增
    public function store(Request $request){
        $baseApi=new BaseApi();
        $pygBrand=new PygBrand();
        //获取数据
        $params=$request->all();

        //数据验证
        //数据验证
        $this->validate($request,[
            "name"=>'required',
            "cate_id"=>'required',
            "is_hot"=>'required',
            "sort"=>'required',
        ],[
            'required' => ':attribute 为必填项',
        ],[
            "name"=>"品牌名",
            "cate_id"=>"所属分类id",
            "is_hot"=>"是否热门  1是  0否",
            "sort"=>"排序"
        ]);
        //生成缩略图
        if(isset($params["logo"])&&!empty($params["logo"])&&is_file('.'.$params["logo"])){
            Image::make($params["logo"])->resize(200,200);
        }
        //插入数据表
        $res=$pygBrand->create($params);
        if(!$res){
            return $baseApi->fail("数据新增失败！",402);
        }
        //返回数据
        $info=$pygBrand->finf($res->id);
        return $baseApi->ok($info);
    }

    //删除品牌
    public function destroy($id){
        $baseApi=new BaseApi();
        $pygBrand=new PygBrand();
        $pygGoods=new PygGoods();


        //判断是否有子级商品
        $totol=$pygGoods->where("brand_id",$id)->count();
        if($totol>0){
            return $baseApi->fail("该品牌不能删除！");
        }

        //执行删除
        $res=$pygBrand->find($id);
        if(!$res){
            return $baseApi->fail("哥哥别瞎搞");
        }
        $a=$res->delete();
        if ($a){
            return $baseApi->ok("成功！");
        }else{
            return $baseApi->fail("删除失败");
        }
    }
}