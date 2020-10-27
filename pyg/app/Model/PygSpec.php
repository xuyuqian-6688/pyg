<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PygSpec extends Model
{
    //
    public $table="pyg_spec";
    public $fillable=["type_id","spec_name","sort"];
    public $timestamps=false;
    //规格名称——规格值
    public function specValues()
    {
        return $this->hasMany("App\Model\PygSpecValue","spec_id","id");
    }


}
