<?php
namespace App\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;//继承基类数据库控制器
use Illuminate\Support\Facades\DB;
class PygAdmin extends Authenticatable implements JWTSubject
{
    public $table="pyg_admin";
    public $fillable=["username","password","role_id","email"];
    public $timestamps=false;
//    public function loginModel($username){
//        $res=PygAdmin::where("username","=",$username)->first();
//        return $res;
//    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    //管理员角色
    public function role()
    {
        //return $this->hasOne("App\Model\PygRole","id",'role_id')->get();//->paginate(10)
        return DB::table("pyg_admin")->leftJoin("pyg_role","pyg_admin.role_id","=","pyg_role.id")->paginate(10);
    }
}