<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PygRole extends Model
{
    //
    public $table="pyg_role";
    public $fillable=["role_name","desc","role_auth_ids",];
    public $timestamps = false;
}
