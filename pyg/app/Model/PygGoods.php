<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PygGoods extends Model
{
    //
    public $table="pyg_goods";

    //商品关联分类
    public function category()
    {
        return $this->belongsTo("App\Model\PygCategory","cate_id","id");
    }
    //商品关联品牌
    public function brand()
    {
        return $this->belongsTo("App\Model\PygBrand","brand_id","id");
    }
    //关联商品类型
    public function types()
    {
        return $this->belongsTo("App\Model\PygType","type_id","id");
    }

}
