<?php
/**
 * 生成字符串
 * @param $len  需要生成的字符串长度
 * @return string
 */
function getStr($len){
    $str = "";
    $chars = "abcdefghijklmnopqrstuvwxyz123ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for($i=0; $i<$len; $i++){
        $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    return $str;
}

function adminPassword($pwd, $salt){
    return md5($salt . md5($pwd));
}

//后台菜单递归
function getAdminMenu($nodes, $pid=0){
    if(empty($nodes)){ return []; }
    $data = [];
    foreach($nodes as $vo){
        if($vo['pid']==$pid){
            $vo['child'] = getAdminMenu($nodes, $vo['id']);
            $data[] = $vo;
        }
    }
    return $data;
}

//节点递归
function sortNode($nodes, $pid=0, $html=''){
    if(empty($nodes)){ return []; }
    $data = [];
    foreach($nodes as $vo){
        if($vo['pid']==$pid){
            $vo['html'] = str_repeat($html, $vo['level']);
            $data[] = $vo;
            $data = array_merge($data , sortNode($nodes, $vo['id'], $html));
        }
    }
    return $data;
}


/**
 * 获取图片路径
 */
function get_img($file){
    if (empty($file)) {
        return '';
    }

    if (strpos($file, "http") === 0) {
        return $file;
    } else if (strpos($file, "/") === 0) {
        return $file;
    } else {
        $picUrl = config('app.pic_url');
        return $picUrl.$file;
    }
}