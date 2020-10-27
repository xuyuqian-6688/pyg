<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PygSpecValue extends Model
{
    //
    public $table="pyg_spec_value";
    public $fillable=["spec_id","spec_value","type_id"];
    public $timestamps=false;

    public function attrbutes()
    {
        return $this->hasMany("App\Model\PygAttrbute","type_id","id");
    }
}
