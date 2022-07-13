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
use Hyperf\View\Engine\ThinkEngine;
use Hyperf\View\Mode;

return [
    'engine' => ThinkEngine::class,
    'mode' => Mode::SYNC,
    'config' => [
        'view_path' => BASE_PATH . '/public/template/',
        'cache_path' => BASE_PATH . '/runtime/view/',

        // 模板后缀
        'view_suffix'   => 'html',
        // 模板文件名分隔符
        'view_depr'     => DIRECTORY_SEPARATOR,
        // 模板引擎普通标签开始标记
        'tpl_begin'     => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'       => '}',
        // 标签库标签开始标记
        'taglib_begin'  => '{',
        // 标签库标签结束标记
        'taglib_end'    => '}',
        // 模板引擎类型使用Think
        //'type'          => 'Think',
        // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写 3 保持操作方法
        //'auto_rule'     => 1,
        'tpl_replace_string'  =>  [
            '__STATIC__'=>'/static',
            '__JS__' => '/static/js',
            '__CSS__' => '/static/css',
            '__IMG__' => '/static/img',
        ]
    ],
];
