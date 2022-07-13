<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
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

    public function error($msg = '', $code = 400, $data = []){
        $returnRes = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return $this->response->json($returnRes);
    }

    public function success($data = [], $msg = 'success',$code=0){
        $returnRes = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return $this->response->json($returnRes);
    }
}
