<?php


namespace App\Http\Controllers\Admin;
use App\Model\PygGoods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PygType;
use App\Model\PygSpec;
use App\Model\PygSpecValue;
use App\Model\PygAttrbute;
use App\Http\Controllers\AdminApi\BaseApi;
use Illuminate\Support\Facades\DB;
class TypesController  extends Controller
{
    //显示商品模型
    public function index()
    {
        $pygType=new PygType();
        $baseApi=new BaseApi();
        //查询数据
        $res=$pygType->get();
        if(!$res){
            return $baseApi->fail("莫得数据！");
        }
        return $baseApi->ok($res);
    }

    //模型详情
    public function show(Request $request,$id){
        $pygType=new PygType();
        $baseApi=new BaseApi();
        $pygAttrbute=new PygAttrbute();

        //查询一条数据
        $info=$pygType->with(["specs.specValues"])->with(["attrs"])->find($id)->toArray(); //,"PygSpec.specValues","PygType.attrbutes"
       /* $info=$pygType->with(["specs"=>function($res){
            $res->specValues;
            $res->attrbutes;
        }])->find($id);*/
//       $info=DB::table("pyg_type")->select("select * from pyg_type
//                and left join pyg_spec t1  on pyg_type.id=t1.type_id and left join pyg_specvaue t2 on t1.id=t2.spec_id
//                and left join pyg_attrbute t3 on pyg_type.id=t3.type_id
//                where id={$id}");
//       //dd($info);
//        foreach ($info as $k => $v){
//            dump($v);
//        }
        //dd($info->with("attrbutes")->get());
        //$info=$info->specValues;

//        $attribute=$pygType->leftJoin("pyg_attribute",function ($jion){
//            $jion->on("pyg_type.id","=","pyg_attribute.type_id");
//        })->where("type_id","16")->get()->toArray();
//       //$attribute=$pygType->with("attrbutes")->find($id);
//        dd($attribute);
//        //dd($info);
//        $info["specs"][0]["attrs"][0]=["id"=>$attribute[0]["id"]];
        return $baseApi->ok($info);
    }


    //商品删除
    public function destroy($id){
        $pygGoods=new PygGoods();
        $baseApi=new BaseApi();
        $pygTypes=new PygType();
        $pygSpec=new PygSpec();
        $pygSpecValue=new PygSpecValue();
        $pygAttrbute=new PygAttrbute();
        $count=$pygGoods->where("type_id",$id)->count();
        if($count>0){
            return $baseApi->fail("不能删除！");
        }

        DB::beginTransaction();
        try{
            //中间逻辑代码
            //删除模型
            $res=$pygTypes->find($id);
            $res->delete();
            //删除规格
            $pygSpec->where("type_id",$id)->delete();
            //删除规格值
            $pygSpecValue->where("type_id",$id)->delete();
            //删除属性
            $pygAttrbute->where("type_id",$id)->delete();
            DB::commit();
            return $baseApi->ok("成功");
        }catch (\Exception $e) {
            //接收异常处理并回滚

            DB::rollBack();
            return $baseApi->fail("哥哥别瞎搞");
        }
    }

    //商品模型新增
    public function store(Request $request)
    {
        $pygGoods=new PygGoods();
        $baseApi=new BaseApi();
        $pygTypes=new PygType();
        $pygSpec=new PygSpec();
        $pygSpecValue=new PygSpecValue();
        $pygAttrbute=new PygAttrbute();

        //接收参数
        $params=$request->all();
        //dd($params['type_name']);
        //return $baseApi->ok($params);

        //数据验证
        $this->validate($request, [
            'type_name|模型名称' => 'require|max:20',
            'spec|规格' => 'require|array',
            'attr|属性' => 'require|array',
        ]);
        //开启事务操作
        DB::beginTransaction();
        try{
            //添加商品模型
            $type=$pygTypes->create($params["type_name"]);
            //添加商品规格名，过滤空字符
            /**
             * //参数数组参考：
                $params = [
                    'type_name' => '手机',
                    'spec' => [
                        0=>['name' => '颜色', 'sort' => 50, 'value'=>['黑色', '白色', '金色']],
                        1=>['name' => '内存', 'sort' => 50, 'value'=>['64G', '128G', '256G']],
                    ],
                    'attr' => [
                        ['name' => '毛重', 'sort'=>50, 'value' => []],
                        ['name' => '产地', 'sort'=>50, 'value' => ['进口', '国产']],
                    ]
                ]
             */
            foreach ($params["spec"] as $key =>$spec){
                //如果spec下面是空的，直接 删除
                if(trim($spec["name"]=='')){
                    unset($params["spec"]);
                    continue;
                }
                foreach ($spec["value"] as $k=>$v){
                    if(trim($v)==''){
                        unset($spec["value"][$k]);
                    }
                }
                if(empty($spec["value"])){
                    unset($params["spec"]["key"]);
                }
            }
            //添加商品规格
            $specs=[];
            $i=0;
            foreach ($params["spec"] as $key=> $value){
                $row=[
                    "type_id"=>$type->id,
                    "spec_name"=>$value["name"],
                    "sort"=>$value["sort"]
                ];
                $specs[]=$row;
                $i++;
            }
            //插入数据
            $spec_model=$pygSpec->insert($specs);

            //商品规格值添加
            $spec_values=[];
            foreach ($params["spec"] as $key =>$spec){
                foreach ($spec["value"] as $k =>$v){
                    $spec_id=DB::table("pyg_spec")->select("SELECT * FROM pyg_spec
                            ORDER BY id DESC
                            LIMIT 0,1");
                    $row=[
                        "spec_id"=>$spec_id+1,
                        "spec_value"=>$v,
                        "type_id"=>$type->id,
                    ];
                    $spec_values[]=$row;
                }
            }
            $pygSpecValue->insert($spec_values);
            //添加商品属性
            //过滤
            foreach ($params["attr"] as $key =>&$attr){
                //如果spec下面是空的，直接 删除
                if(trim($attr["name"]=='')){
                    unset($params["attr"][$key]);
                    //continue;
                }else{
                    foreach ($spec["value"] as $k=>$v){
                        if(trim($v)==''){
                            unset($params['attr'][$key][$v][$k]);
                        }
                    }
                }
            }
            unset($attr);
            //批量添加属性名称属性值
            $attrs=[];
            foreach ($params["attr"] as $key =>$attr){
                $row=[
                    "attr_name"=>$attr["name"],
                    "attr_values"=>implode(",",$attr["value"]),
                    "sort"=>$attr["sort"],
                    "type_id"=>$type->id,
                ];
                $attrs[]=$row;
            }
            $pygAttrbute->insert($attrs);
            DB::commit();
            return $baseApi->ok($type);
        }catch (\Exception $e) {
            //接收异常处理并回滚

            DB::rollBack();
            $msg=$e->getMessage();
            return $baseApi->fail($msg);
        }
    }

    //商品模型修改
    public function update(Request $request,$id){
        $pygGoods=new PygGoods();
        $baseApi=new BaseApi();
        $pygTypes=new PygType();
        $pygSpec=new PygSpec();
        $pygSpecValue=new PygSpecValue();
        $pygAttrbute=new PygAttrbute();

        //接收参数
        $params=$request->all();
        //参数检测
        //数据验证
        $this->validate($request, [
            'type_name|模型名称' => 'require|max:20',
            'spec|规格' => 'require|array',
            'attr|属性' => 'require|array',
        ]);
        //开启事务
        DB::beginTransaction();
        try{
            //修改模型名称
            $pygTypes->where("id",$id)->update($params["type_name"]);
            //参数值过滤
            foreach ($params as $k => $spec){
                //规格名称
                if(trim($spec["name"])==''){
                    unset($params["spec"][$k]);
                    continue;
                }else{
                    //规格值
                    foreach ($spec["value"] as $key =>$value){
                        if(trim($value)==''){
                            unset($params["spec"][$k]["value"][$key]);
                        }

                    }
                }
                //判断规格值是否是空数组
                if ($params["spec"][$k]["value"]){
                    unset($params["spec"][$k]);
                }
            }
            //批量删除规格名
            $pygSpec->where("type_id",$id)->delete();
            //批量添加规格值
            $specs=[];
            foreach ($params["spec"] as $i=>$spec){
                $row=[
                    "spec_name"=>$spec["name"],
                    "sort"=>$spec["sort"],
                    "type_id"=>$id,
                ];
                $specs[]=$row;
            }
            $pygSpec->insert($specs);
            //批量删除规格值
            $pygSpecValue->where("type_id",$id)->delete();
            $spec_values=[];
            foreach ($params["spec"] as $i=>$spec){
                foreach ($spec["value"] as $value){
                    $row=[
                        "spec_id"=>19,
                        "type_id"=>$id,
                        "spec_value"=>$value,
                    ];
                    $spec_values[]=$row;
                }
            }
            $pygSpecValue->insert($spec_values);
            //过滤空的规格属性
            foreach($params['attr'] as $i=>$attr){
                if(trim($attr['name']) == ''){
                    unset($params['attr'][$i]);
                    continue;
                }else{
                    foreach($attr['value'] as $k=>$value){
                        if(trim($value) == ''){
                            unset($params['attr'][$i]['value'][$k]);
                        }
                    }
                }
            }
            //批量删除原来的属性
            $pygAttrbute->where("type_id")->delete();
            //批量添加新的属性
            $attrs = [];
            foreach($params['attr'] as $i=>$attr){
                $row = [
                    'type_id' => $id,
                    'attr_name' => $attr['name'],
                    'attr_values' => implode(',', $attr['value']),
                    'sort' => $attr['sort']
                ];
                $attrs[] = $row;
            }
            $pygAttrbute->insert($attrs);
            //返回数据
            $data=$pygTypes->find($id);
            DB::commit();
            return $baseApi->ok($data);
        }catch (\Exception $e) {
            //接收异常处理并回滚
            $msg=$e->getMessage();
            DB::rollBack();
            return $baseApi->fail($msg);
        }
    }
}