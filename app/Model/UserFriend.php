<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class UserFriend extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_friends';
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

    // 获取好友列表
    public function getFriends($user_id){
        return self::query()
            ->join('user', "user_friends.friend_id","=","user.id")
            ->where('user_id', $user_id)
            ->get()->toArray();
    }
}