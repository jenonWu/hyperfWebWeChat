<?php
declare(strict_types=1);
namespace App\Admin\Controller;

use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\Utils\ApplicationContext;
use HyperfExt\Captcha\CaptchaFactory;
use App\Model\Admin;

class CommonController extends BaseController
{
    /**
     * 登录界面
     * @return mixed
     */
    public function login(){
        if($this->session->get('admin')) {
            return $this->response->redirect('admin/index/index');
        }
        if($this->request->cookie('admin')) {
            $cookie = new Cookie('admin', $this->request->cookie('admin'));
        }
        if (isset($cookie)){
            $name = unserialize($this->request->cookie('admin'));
            $this->session->set('admin',$name);
            return $this->response->redirect('admin/index/login');
        }

        return $this->view('');
    }

    /**
     * 登录
     */
    public function doLogin(){
        $username = $this->request->input('username', '');
        $password = $this->request->input('password', '');
        $remember = $this->request->input('remember', false);
        if(config('app.admin_captcha')){
            $captcha_key = $this->request->input('captcha_key', '');
            $captcha = $this->request->input('captcha', '');
            $captchaFactory = ApplicationContext::getContainer()->get(CaptchaFactory::class);
            if(!$captchaFactory->validate($captcha_key, $captcha)){
                return $this->error('验证码错误', 100);
            }
        }

        $adminInfo = Admin::query()->where('username', $username)->first();
//        var_dump($adminInfo);
        if(empty($adminInfo)){
            return $this->error('管理员不存在', 101);
            return ['code'=>0, 'msg'=>'管理员不存在'];
        }

        if(adminPassword($password, $adminInfo['password_salt']) !== $adminInfo['password']){
            return $this->error('密码错误', 102);
        }


        //登入成功页面跳转
        if(!empty($remember)){
            $cookie = new Cookie('admin', serialize($adminInfo));
        }else{
            $cookie = null;
        }
        $this->session->set('admin', $adminInfo);

        $adminInfo->last_login_time = time();
        $adminInfo->login_times++;
        $adminInfo->save();

        return $this->success(['url' =>'/admin/index/index',], '登录成功')->withCookie($cookie);
    }

    /**
     * 验证码
     */
    public function captchaImg(){
        $captchaFactory = ApplicationContext::getContainer()->get(CaptchaFactory::class);

        $captcha = $captchaFactory->create();
        $response = [
            'key' => $captcha->getKey(),
            'blob' => $captcha->getBlob()->toDataUrl(),
            'ttl' => $captcha->getTtl(),
        ];

        return $response;

    }

    /**
     * 响应跳转界面
     */
    public function jump(){
        $data = $this->request->all();
        if (!empty($data['param'])){
            $data = json_decode(base64_decode($data['param']),true);
        }
//        var_dump($data);
        if (empty($data)){
            $data = [
                'code'=>400,
                'msg' =>'未知错误',
                'wait'=>'3',
                'url' =>'/'
            ];
        }else{
            $msgwait = ($data['code']==0)?'成功':'未知错误';
            $msg = !empty($data['msg'])?$data['msg']:$msgwait;
            $data = [
                'code'=>$data['code'],
                'msg' =>$msg,
                'wait'=>!empty($data['wait'])?$data['wait']:'3',
                'url' =>!empty($data['url'])?$data['url']:'/'
            ];
        }

        return $this->render->render('/admin/common/dispatch_jump',$data);
    }

    /**
     * 管理员退出，清除名字为admin的session
     * @return [type] [description]
     */
    public function logout()
    {
        $this->session->clear();
        $cookie = new Cookie('admin', '');
        if($this->session->has('admin')) {
            return $this->error('退出失败')->withCookie($cookie);
        } else {
            return $this->success(['url'=>'/admin/common/login'],'正在退出...', 'http')->withCookie($cookie);
        }
    }
}