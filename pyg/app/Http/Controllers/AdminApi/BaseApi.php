<?php
namespace App\Http\Controllers\AdminApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseApi extends Controller
{
    /**
     * @param string $code
     * @param string $msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($code="200",$msg="success",$data=[]){
        /**
         * 封装通用的响应
         */
        $res=[
            "code"=>$code,
            "msg"=>$msg,
            "data"=>$data
        ];
        return response()->json($res);//终止往下执行
    }

    /**
     * @param array $data
     * @param string $msg
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function ok($data=[],$msg="success",$code="200")
    {
        /**
         * 成功的响应
         */
        return $this->response($code,$msg,$data);
    }

    /**
     * @param $msg
     * @param string $code
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function fail($msg,$code="500",$data=[])
    {
        /**
         * 失败的响应
         */
        return $this->response($code,$msg,$data);
    }

    ////递归函数 实现无限级分类列表
    public function get_cate_list($list,$pid=0,$level=0) {
        static $tree = array();
        foreach($list as $row) {
            if($row['pid']==$pid) {
                $row['level'] = $level;
                $tree[] = $row;
                $this->get_cate_list($list, $row['id'], $level + 1);
            }
        }
        return $tree;
    }
    //引用方式实现 父子级树状结构
    public function get_tree_list($list){
        //将每条数据中的id值作为其下标
        $temp = [];
        foreach($list as $v){
            $v['son'] = [];
            $temp[$v['id']] = $v;
        }
        //获取分类树
        foreach($temp as $k=>$v){
            $temp[$v['pid']]['son'][] = &$temp[$v['id']];
        }
        return isset($temp[0]['son']) ? $temp[0]['son'] : [];
    }
}