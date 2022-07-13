<?php

declare(strict_types=1);

namespace App\Controller;
use App\Model\User;
use App\Model\UserFriend;

use Hyperf\Utils\ApplicationContext;

class CommonController extends AbstractController
{
    //登录
    public function login()
    {
        $mobile = $this->request->input('mobile');
        $password = $this->request->input('password');
        $user = User::query()->where('mobile', $mobile)->first();
        if(empty($user)){
            return $this->error("手机号不存在");
        }

        if(md5($password.$password) != $user->password){
            return $this->error("密码错误");
        }

        $user->last_login_time = time();
        $user->login_times++;
        $user->token = getStr(32);

        $res = $user->save();
        if(!$res){
            return $this->error("登录失败");
        }

        // 获取好友列表
        $UserFriendsModel = new UserFriend();
        $friends = $UserFriendsModel->getFriends($user->id);

        $container = ApplicationContext::getContainer();
        $cache = $container->get(\Psr\SimpleCache\CacheInterface::class);
        $cache->set('userToken_'.$user->token, json_encode([
            "id"=>$user->id,
            "mobile"=>$user->mobile,
            "nickname"=>$user->nickname,
            "avatar"=>$user->avatar
        ]), 21600);

        return $this->success([
            "token"=>$user->token,
            "id"=>$user->id,
            "mobile"=>$user->mobile,
            "nickname"=>$user->nickname,
            "avatar"=>$user->avatar,
            "friends"=>$friends
        ]);

    }

    //注册
    public function register(){
        $data = $this->request->all();
        if(empty($data['mobile'])){
            return $this->error('手机号不能为空');
        }
        if(strlen($data['mobile']) != 11 || !preg_match("/^1[3456789]{1}\d{9}$/", $data['mobile'])){
            return $this->error('手机号格式不正确');
        }
        if(empty($data['nickname'])){
            return $this->error('用户名不能为空');
        }
        if(empty($data['password'])){
            return $this->error('密码不能为空');
        }
        if(strlen($data['password'])<6){
            return $this->error('密码不能小于6位数');
        }
        if(empty($data['password2']) || $data['password']!=$data['password2']){
            return $this->error('确认密码不正确');
        }

        $res = User::query()->where('mobile', $data['mobile'])->first();
        if($res){
            return $this->error('手机号已被注册');
        }

        $user = new User();

        $user->mobile = $data['mobile'];
        $user->avatar = "static/common/images/user-head".mt_rand(0,8).".jpg";
        $user->nickname = $data['nickname'];
        $user->password = md5($data['password'].$data['password']);
        $user->create_time = time();
        $saveRes = $user->save();
        if($saveRes){
            return $this->success();
        }else{
            return $this->error('注册失败');
        }

    }
}
