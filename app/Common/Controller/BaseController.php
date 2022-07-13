<?php
declare(strict_types=1);
namespace App\Common\Controller;

use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Hyperf\View\Engine\ThinkEngine;
use Hyperf\View\RenderInterface;


abstract class BaseController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @Inject()
     * @var RenderInterface
     */
    protected $render;

    /**
     * @Inject()
     * @var SessionInterface
     */
    protected $session;

    /**
     * 视图模板
     * @param string $template 模板地址
     * @param array $data 参数
     * @return mixed
     */
    protected function view(string $template='', array $data = []){
        if($template == ''){
            return $this->render->render($this->request->path(), $data);
        }
        return $this->render->render($template, $data);
    }

    /**
     * 成功响应
     * @param array $data   正确返回数据
     * @param string $msg   正确返回提示
     * @param string $requestType   请求类型 api:json返回   http:页面跳转提示
     * @param int $code     错误码
     * @return \Psr\Http\Message\ResponseInterface
     */
    final public function success($data = [], $msg = 'success', $requestType = 'api',$code = 0)
    {
        switch ($requestType){
            case 'api':
                $returnRes = [
                    'code' => $code,
                    'msg' => $msg,
                    'data' => $data
                ];
                return $this->response->json($returnRes);
                break;
            case 'http':
                $returnRes = [
                    'code' => $code,
                    'msg' => $msg,
                    'url' => !empty($data['url'])?$data['url']:'/',
                    'wait' => !empty($data['wait'])?$data['wait']:'3',
                ];
                $param = base64_encode(json_encode($returnRes));
                return $this->response->redirect('/jump?param='.$param);
                break;
        }
    }

    /**
     * 错误响应
     * @param string $msg   错误提示
     * @param array $data   错误数据
     * @param string $requestType   请求类型 api:json返回   http:页面跳转提示
     * @param int $code     错误码
     * @return \Psr\Http\Message\ResponseInterface
     */
    final public function error($msg = '', $code = 400, $data = [], $requestType = 'api')
    {
        switch ($requestType){
            case 'api':
                $returnRes = [
                    'code' => $code,
                    'msg' => $msg,
                    'data' => $data
                ];
                return $this->response->json($returnRes);
                break;
            case 'http':
                $returnRes = [
                    'code' => $code,
                    'msg' => $msg,
                    'url' => !empty($data['url'])?$data['url']:'/',
                    'wait' => !empty($data['wait'])?$data['wait']:'3',
                ];
                $param = base64_encode(json_encode($returnRes));
                return $this->response->redirect('/jump?param='.$param);
                break;
        }
    }
}