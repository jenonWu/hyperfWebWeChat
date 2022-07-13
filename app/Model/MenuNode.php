<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use App\Model\RoleNode;

/**
 */
class MenuNode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menu_node';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public $timestamps = false;


    public function  roleMenu($role_id=1){
        if($role_id != 1){
            return self::query()
                ->join('role_node', function($join)use ($role_id){
                    $join->on('menu_node.id', '=', 'role_node.node_id')->where('role_node.role_id', '=', $role_id);
                })
                ->where('status', 1)
                ->orderBy("sort", 'asc')->get();
        }else{
            //获取节点菜单
            return self::query()->where('status', 1)->orderBy("sort", 'asc')->get();
        }

    }

    public function  getAuthorize($role_id=1){
        return self::query()->select("menu_node.*", "role_node.role_id as role")
            ->leftJoin('role_node', function($join)use ($role_id){
                $join->on('menu_node.id', '=', 'role_node.node_id')->where('role_node.role_id', '=', $role_id);
            })
            ->where('status', 1)
            ->orderBy("sort", 'asc')->get()->toArray();

    }
}