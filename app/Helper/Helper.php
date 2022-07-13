<?php

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Server\ServerFactory;
use Hyperf\Utils\ApplicationContext;
use Hyperf\HttpServer\Contract\RequestInterface;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

/**
 * 容器实例
 */
if (!function_exists('url')) {
    function url($url, $params = [], $tag='&')
    {
        $constr = '?';
        if((strpos($url,'?') !== false)){
            $constr = '&';
        }
        $str = '';
        foreach ($params as $k=>$v) {
            if (empty($str)){
                $str = $constr . $k . '=' .$v;
            }else{
                $str .= $tag . $k . '=' .$v;
            }
        }
        if (empty($url)){
            return '';
        }
        if ($url[0]=='/'){
            return $url.$str;
        }elseif((strpos($url,'http://') !== false)||(strpos($url,'https://') !== false)){
            return $url.$str;
        }
        else{
            return '/'.$url.$str;
        }
    }
}

function list_to_tree($list, $root = 0, $pk = 'id', $pid = 'pid', $child = 'children')
{
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}




function get_top_menu($list)
{
    // $top_list
    $top_list = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            if ($data['pid']==0){
                $top_list[] = $data;
            }
        }
    }
    return $top_list;
}



/**
 * 容器实例
 */
if (!function_exists('container')) {
    function container()
    {
        return ApplicationContext::getContainer();
    }
}

/**
 * redis 客户端实例
 */
if (!function_exists('redis')) {
    function redis()
    {
        return container()->get(Redis::class);
    }
}

/**
 * server 实例 基于 swoole server
 */
if (!function_exists('server')) {
    function server()
    {
        return container()->get(ServerFactory::class)->getServer()->getServer();
    }
}

/**
 * websocket frame 实例
 */
if (!function_exists('frame')) {
    function frame()
    {
        return container()->get(Frame::class);
    }
}

/**
 * websocket 实例
 */
if (!function_exists('websocket')) {
    function websocket()
    {
        return container()->get(WebSocketServer::class);
    }
}

/**
 * 缓存实例 简单的缓存
 */
if (!function_exists('cache')) {
    function cache()
    {
        return container()->get(Psr\SimpleCache\CacheInterface::class);
    }
}

/**
 * 控制台日志
 */
if (!function_exists('stdLog')) {
    function stdLog()
    {
        return container()->get(StdoutLoggerInterface::class);
    }
}

/**
 * 文件日志
 */
if (!function_exists('logger')) {
    function logger()
    {
        return container()->get(LoggerFactory::class)->make();
    }
}

/**
 *
 */
if (!function_exists('request')) {
    function request()
    {
        return container()->get(RequestInterface::class);
    }
}

/**
 *
 */
if (!function_exists('response')) {
    function response()
    {
        return container()->get(ResponseInterface::class);
    }
}

if (!function_exists('getClientIp')) {
    function getClientIp()
    {
        try {
            /**
             * @var ServerRequestInterface $request
             */
            $request = Context::get(ServerRequestInterface::class);
            $ip_addr = $request->getHeaderLine('x-forwarded-for');
            if (verifyIp($ip_addr)) {
                return $ip_addr;
            }
            $ip_addr = $request->getHeaderLine('remote-host');
            if (verifyIp($ip_addr)) {
                return $ip_addr;
            }
            $ip_addr = $request->getHeaderLine('x-real-ip');
            if (verifyIp($ip_addr)) {
                return $ip_addr;
            }
            $ip_addr = $request->getServerParams()['remote_addr'] ?? '0.0.0.0';
            if (verifyIp($ip_addr)) {
                return $ip_addr;
            }
        } catch (Throwable $e) {
            return '0.0.0.0';
        }
        return '0.0.0.0';
    }
}
/**
 * 管理员密码加密方式
 * @param $password  密码
 * @param $password_code 密码额外加密字符
 * @return string
 */
if (!function_exists('password')) {
    function password($password, $password_code = 'lshi4AsSUrUOwWV')
    {
        return md5(md5($password) . md5($password_code));
    }
}
if (!function_exists('rcache')) {
    function rcache($key, $value, $ttl=null)
    {
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        $cache = $container->get(\Psr\SimpleCache\CacheInterface::class);
        return $cache->set($key, $value, $ttl);
    }
}

if (!function_exists('dcache')) {
    function dcache($key, $value, $ttl=null)
    {
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        $cache = $container->get(\Psr\SimpleCache\CacheInterface::class);
        return $cache->set($key, $value, $ttl);
    }
}

if (!function_exists('success')) {
    function success(array $data = [], string $msg = '成功',$code=200)
    {
        $res            = [];
        $res['code']    = $code;
        $res['data']    = $data? $data: '';
        $res['message'] = (string) $msg;
        return $res;
    }
}

if (!function_exists('error')) {
    function error(string $msg = '失败', int $code = 400, array $data = [])
    {
        $res            = [];
        $res['code']    = (int) $code;
        $res['data']    = $data? $data: '';
        $res['message'] = (string) $msg;
        return $res;
    }
}

if (!function_exists('calc_distance')) {
    /**
     * 获取两个经纬度之间的距离
     * @param string $lat1 纬一
     * @param String $lng1 经一
     * @param String $lat2 纬二
     * @param String $lng2 经二
     * @return float 返回两点之间的距离
     */
    function calc_distance(string $lat1, string $lng1, string $lat2, string $lng2) {
        /** 转换数据类型为 double */
        $lat1 = doubleval($lat1);
        $lng1 = doubleval($lng1);
        $lat2 = doubleval($lat2);
        $lng2 = doubleval($lng2);
        /** 以下算法是 Google 出来的，与大多数经纬度计算工具结果一致 */
        $theta = $lng1 - $lng2;
        $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist  = acos($dist);
        $dist  = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344);
    }
}

if (!function_exists('callback')) {
    function callback($state = true, $extend_data = [], $msg = ''){
        $data['state'] = $state;
        $data['data']  = $extend_data;
        $data['msg']   = $msg;
        return $data;
    }
}


if (!function_exists('phone_desensitization')) {

    /**
     * 手机号脱敏
     * @date 2019-12-17 14:51
     * @param $phone
     * @return mixed|string
     */
    function phone_desensitization($phone)
    {
        $customer_phone = '';
        if (strlen($phone) > 7) {
            $customer_phone = substr_replace($phone, '****', 3, 4);
        } else {
            $customer_phone = $phone;
        }
        return $customer_phone;
    }
}




if (!function_exists('request_unlock')) {
    /**
     * 删除http请求锁
     * @date   2020/1/10 17:04
     * @param $key
     * @return int
     */
    function request_unlock($key): int
    {
        return \Hyperf\Utils\ApplicationContext::getContainer()
            ->get(\App\Common\Library\Cache\CacheFactory::class)
            ->getCacheObject()
            ->del($key);
    }
}

/**
 * 管理员操作日志
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function addlog($request,$admin_id,$action_msg)
{
    $AdminMenuModel = new \App\Server\Model\AdminMenu();
    $AdminMenu = $AdminMenuModel->getCacheList([],'menu_all');
    $AdminMenu = array_column($AdminMenu,null,'router');
    $AdminModel = new \App\Server\Model\Admin();
    $AdminInfo = $AdminModel->getCacheInfo([['id','=',$admin_id]], 'admin_info_id_'.$admin_id);
    $router = $request->getRequestUri();
    $data = [];
    $data['admin_menu_id'] = !empty($AdminMenu[$router]['id'])?$AdminMenu[$router]['id']:0;
    $data['router']     = $router;
    $data['admin_id']   = $AdminInfo['id'];  //管理员id
    $data['admin_name'] = $AdminInfo['nickname'].'-'.$AdminInfo['name'];//管理员name
    $data['action_msg'] = $action_msg;       //管理员id
    $data['ip'] = getClientIp();//操作ip
    $data['created_at'] = time();
    $AdminLog = new \app\Server\Model\AdminLog();
    return $AdminLog->dataAdd($data);
}



/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 *
 * @param string $hash 文件头
 * @return string
 * @author: cuitao
 * @date: 2020/4/25 下午6:42
 */
function getUniqidCode($hash="G"){
    //定义一个包含大小写字母数字的字符串
    $chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    //把字符串分割成数组
    $newchars=str_split($chars);
    //打乱数组
    shuffle($newchars);
    //从数组中随机取出15个字符
    $chars_key=array_rand($newchars,15);
    //把取出的字符重新组成字符串
    $fnstr = '';
    for($i=0;$i<15;$i++){
        $fnstr.=$newchars[$chars_key[$i]];
    }
    //输出文件名并做MD5加密
    return $hash.md5($fnstr.time().(microtime()*1000000));
}

//生成不重复Id
function getOrderId($prefix = 'DD')
{
    return $prefix . (date('YmdHis', time())) . substr(microtime(), 2, 6) . sprintf('%03d', rand(0, 999));
}


//生APP用户token
function getToken()
{
    //strtoupper转换成全大写的
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    return substr($charid, 0, 8) . substr($charid, 8, 4) . substr($charid, 12, 4) . substr($charid, 16, 4) . substr($charid, 20, 12);
}

