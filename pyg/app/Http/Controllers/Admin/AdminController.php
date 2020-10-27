<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\PygAdmin;
use App\Http\Controllers\AdminApi\BaseApi;

class AdminController extends Controller
{
    //管理员列表显示
    public function index(Request $request){
        $baseApi=new BaseApi();
        $pygAdmin=new PygAdmin();
        //当前页码数
        $page=$request->all();
        //$pages=$page['page'];
        if($page==1||$page==null){
            $pages=1;
        }else{
            $pages=$page['page'];
        }
        //dump($page);
        //获取总页码数
        $total=$pygAdmin->count('*'); //10
        //dd($total);
        //每页显示条数
        $per_page=$pygAdmin->paginate(10)->toArray(); //对象集合 role(5)
        //dd($per_page);
        //dd($per_page["data"]["password"]);
        //unset($per_page["data"]["password"]);
        //dd($per_page);
        //总页数
        $addNum=ceil($total/2);
        //dd($pygAdmin->perPage());
        //dd($addNum);
        //拼接总数居
        //$per_page["data"]["id"]=$per_page["data"]["role_id"];
        $arr=['total'=>$total,'per_page'=>$pages,'current_page'=>$per_page['current_page'],'last_page'=>$addNum,'data'=>$per_page['data']];
        return $baseApi->ok($arr);

    }

    //管理员详情
    public function show($id)
    {
        $baseApi=new BaseApi();
        $pygAdmin=new PygAdmin();
        $data=$pygAdmin->find($id);
        //dd($data);
        if(!$data){
            return $baseApi->fail("哥哥你别瞎搞！");
        }
        unset($data["password"]);
        $data=$data->toArray();
        return $baseApi->ok($data);
    }

    //管理员新增
    public function store(Request $request)
    {
        $baseApi=new BaseApi();
        $pygAdmin=new PygAdmin();
        //获取
        $params=$request->all();
        //判断用户名是否存在
        $username=$pygAdmin->where("username","=",$params["username"])->first();
        //验证 初始密码为123456
        if($params['username']==''||$username==true){
            return $baseApi->fail("哥哥你瞎搞！");
        }
        if($params['role_id']==''){
            return $baseApi->fail("哥哥你瞎搞，职位不能为空！");
        }

        //数据新增
            //密码加密
        $params['password']=md5($params["password"]);
        $res=$pygAdmin->create($params);
        if(!$res){
            return $pygAdmin->fail("哥哥你别瞎搞");
        }
        $info=$pygAdmin->find($res->id)->toArray();
        unset($info["password"]);
        return $baseApi->ok($info);

    }

    //管理员修改
    public function update(Request $request,$id)
    {
        $baseApi=new BaseApi();
        $pygAdmin=new PygAdmin();
        //获取数据
        $params=$request->all();

        //验证规则，都是可选的，不用验证
        //修改
        $info=$pygAdmin->find($id);
        $info->fill($params);
        $res=$info->save();
        if(!$res){
            return $baseApi->fail("哥哥你别瞎搞");
        }
        $info=$pygAdmin->find($info->id);
        return $baseApi->ok($info);

    }

    //删除操作
    /*public function destroy(Request $request,$id)
    {
        $baseApi=new BaseApi();
        $pygAdmin=new PygAdmin();
        //
        $res=$pygAdmin->find($id);
        if(!$res){
            return $baseApi->fail("哥哥别瞎搞");
        }
        $a=$res->delete();
        if ($a){
            return $baseApi->ok("成功！");
        }else{
            return $baseApi->fail("删除失败");
        }
    }*/
}

