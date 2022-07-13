<?php
declare(strict_types=1);

namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

use Hyperf\Utils\ApplicationContext;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public $cache;

    public function __construct()
    {
        $this->cache = ApplicationContext::getContainer()->get(\Psr\SimpleCache\CacheInterface::class);
    }

    public function onMessage($server, Frame $frame): void
    {
        if($frame->data == 'Live'){
            return;
        }

        $data = json_decode($frame->data,true);
//        var_dump($data);
        if($data['type']==0){   //聊天
            $userToken = $this->cache->get('fd2token_'.$frame->fd);
            $userInfo = $this->cache->get('userToken_'.$userToken);
            $user = json_decode($userInfo, true);
            if($data['friend_id'] == 0 ){   // 大厅聊天
                foreach($server->connections as $fd) {
                    // isEstablished : 检查连接是否为有效的 WebSocket 客户端连接。
                    // 此函数与 exist 方法不同，exist 方法仅判断是否为 TCP 连接，无法判断是否为已完成握手的 WebSocket 客户端。
                    if($server->isEstablished($fd)){
                        $server->push($fd, json_encode([
                            'code'=>0,
                            'type'=>0,
                            'to_id'=>0,
                            'form_id'=>$user['id'],
                            'form_nickname'=>$user['nickname'],
                            'form_avatar'=>$user['avatar'],
                            'content'=>$data['content'],
                            'time'=>time()
                        ]));
                    }
                }
            }else{      //好友聊天
                $friend_id = $data['friend_id'];
                $fd = $this->cache->get('userId2fd_'.$friend_id);
                if(!empty($fd) && $server->isEstablished($fd)){
                    $returnData = [
                        'code'=>0,
                        'type'=>1,
                        'to_id'=> $friend_id,
                        'form_id'=>$user['id'],
                        'form_nickname'=>$user['nickname'],
                        'form_avatar'=>$user['avatar'],
                        'content'=>$data['content'],
                        'time'=>time(),
                    ];
                    $server->push($fd, json_encode($returnData));

                }else{
                    $server->push($frame->fd, json_encode([
                        'code'=>1001,
                        'msg'=>'好友不在线'
                    ]));
                }
            }
        }

    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        var_dump('closed');
    }

    public function onOpen($server, Request $request): void
    {
        if(empty($request->get['token'])){
            $returnData = ['code'=>1000, 'msg'=> '请先登录'];
            $server->push($request->fd, json_encode($returnData));
            $server->close($request->fd);
            return;
        }

//        $cache = ApplicationContext::getContainer()->get(\Psr\SimpleCache\CacheInterface::class);
        $userInfo = $this->cache->get('userToken_'.$request->get['token']);
        if(empty($userInfo)){
            $returnData = ['code'=>1000, 'msg'=> '请先登录'];
            $server->push($request->fd, json_encode($returnData));
            $server->close($request->fd);
            return;
        }
//
//        ($userInfo);
        $userInfo = json_decode($userInfo, true);
        $this->cache->set("fd2token_".$request->fd,  $request->get['token'], 3600);
        $this->cache->set("userId2fd_".$userInfo['id'], $request->fd, 3600);

        echo "OPEN: clientid: {$request->fd} --- ".date('Y-m-d H:i:s')."\n";
    }

    // 推送消息
    // code 0正常  1000:未登录
    //
    public function pushData($content, $code=0){
        return [
            'code'=>$code,
            'time'=>time(),
//            'friend_id'=>$this->_userId,
//            'friend_nickname'=>$this->_userData['nickname'],
//            'friend_head'=>$this->_userData['head_img'],
//            'type'=>0,	//0发大厅  1发好友
            'content'=>$content,
        ];
    }
}
