<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PygCategory extends Model
{
    //
    public $table="pyg_category";
    public $fillable=["cate_name","pid","is_show","is_hot","sort","image_url"];
    public $timestamps=false;


    //一对多
    public function brands()
    {
        return $this->hasMany("App\Model\PygBrand","cate_id","id");
    }
}
