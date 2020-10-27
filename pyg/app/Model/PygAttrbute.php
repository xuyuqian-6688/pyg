<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PygAttrbute extends Model
{
    //
    public $table="pyg_attribute";
    public $fillable=["attr_name","type_id","attr_values","sort"];
    public $timestamps=false;
}
