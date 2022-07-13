<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Model\Admin;
use App\Model\MenuNode;
use App\Admin\Controller\BaseController;
use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Context;

class CheckLogin extends BaseController  implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 是否登录
        if($this->session->get('admin') == false)
        {
            if($this->request->cookie('admin')) {
                $cookie = new Cookie('admin', $this->request->cookie('admin'));
            }
            if (isset($cookie)){
                $name = unserialize($this->request->cookie('admin'));
                $this->session->set('admin',$name);
            }else{
                return $this->response->redirect('admin/common/login');
            }
        }

        $adminInfo = $this->session->get("admin");
        $adminInfo = Admin::query()->find($adminInfo['id']);
        Context::set('adminInfo', $adminInfo);
        if(empty($adminInfo) || $adminInfo['status'] != 1){
            $this->session->forget('admin');
            return $this->response->redirect('admin/common/login');
        }

        if ($adminInfo['role_id']!=1 && !$this->checkAccess($adminInfo['role_id'])) {
            return $this->response->redirect('admin/index/index');
        }

        return $handler->handle($request);
    }

    /**
     * 获取控制器及方法
     */
    private function getPath(){
        $controller = '';    //控制器
        $action = '';       //方法
        $controllerPath="";     //控制器类文件
        $routePath = $this->request->getAttribute(Dispatched::class)->handler->callback;
        if(is_array($routePath) && count($routePath)>=2){
            $controllerPath = $routePath[0];
            $action = $routePath[1];
        }else{
            $controllerMethod = explode('@',$routePath);
            if(!empty($controllerMethod[1])){
                $action = $controllerMethod[1];
                $controllerPath = $controllerMethod[0];
            }else{
                $controllerMethod = explode('::',$routePath);
                if(!empty($controllerMethod[1])){
                    $action = $controllerMethod[1];
                    $controllerPath = $controllerMethod[0];
                }
            }
        }

        if($controllerPath != ''){
            $controllerPathArr = explode("\\",$controllerPath);
            $controlleName = $controllerPathArr[count($controllerPathArr)-1];
            $controller = str_replace('controller', '', strtolower($controlleName));
        }

        return [
            "controller"=>$controller,
            "action"=>$action,
        ];
    }

    /**
     *  检查后台用户访问权限
     * @param int $adminId 后台用户id
     * @return boolean 检查通过返回true
     */
    private function checkAccess($role_id)
    {
        $getPath = $this->getPath();
//        var_dump($getPath);
        $controller = $getPath['controller'];
        $action     = $getPath['action'];
        $rule       = strtolower($controller .'/'. $action);

        $notRequire = ["index/index", "index/welcome", 'index/clearcache', 'index/switchdb'];   //需要全为小写
//        $notRequire = [];   //需要全为小写
        if (!in_array($rule, $notRequire, false)) {

            $res = Db::select("select a.id from menu_node a inner join role_node b on a.id=b.node_id where controller=? and action=? and role_id=? limit 0, 1", ['user', 'index',2]);

            if(!empty($res)){
                return true;
            }else{
                return false;
            }
        } else {
            return true;
        }
    }
}