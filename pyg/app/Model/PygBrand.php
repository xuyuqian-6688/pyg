<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PygBrand extends Model
{
    //
    public $table="pyg_brand";

    public $fillable=["name","cate_id","desc","is_hot","sort","logo","url"];
    public $timestamps=false;

    public function PygCategory(){
        return $this->hasOne("App\Model\PygCategory","id","cate_id"); //->bind(),默认把第二张表的字段合并到一块，形成一维数组
    }
}
