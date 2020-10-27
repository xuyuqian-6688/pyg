<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PygAuth extends Model
{
    //
    public $table="pyg_auth";
    public $fillable=["pid","level","pid_path","auth_c","auth_a","auth_name"];
    public $timestamps = false;

}
