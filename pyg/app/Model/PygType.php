<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\PygSpecValue;
class PygType extends Model
{
    //
    public $table="pyg_type";
    public $fillable=["type_name"];
    public $timestamps=false;
    //一个类型有多个规格名
    public function specs()
    {
        return $this->hasMany("App\Model\PygSpec","type_id","id");
    }
    //一个类型下有多个属性
    public function attrs()
    {
        return $this->hasMany("App\Model\PygAttrbute","type_id","id");
    }
}
