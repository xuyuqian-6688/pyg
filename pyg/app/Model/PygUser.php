<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PygUser extends Model
{
    //
    public $table="pyg_user";
    public $timestamps=false;
    public $fillable=["phone","username","password","openid"];
}
