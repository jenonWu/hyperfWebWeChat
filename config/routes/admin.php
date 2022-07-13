<?php
declare(strict_types = 1);

use Hyperf\HttpServer\Router\Router;
use App\Middleware\Auth\CheckLogin;
use App\Admin\Controller\IndexController;

Router::get('/jump', 'App\Admin\Controller\CommonController::jump');

Router::addGroup('/admin/', function(){
    Router::get('common/login', 'App\Admin\Controller\CommonController::login');
    Router::get('common/captchaImg', 'App\Admin\Controller\CommonController::captchaImg');
    Router::post('common/dologin', 'App\Admin\Controller\CommonController::dologin');
});

Router::addGroup('/admin/', function(){
    Router::get('', 'App\Admin\Controller\IndexController::index');
    Router::get('index/index', 'App\Admin\Controller\IndexController::index');
    Router::get('index/welcome', 'App\Admin\Controller\IndexController::welcome');
    Router::get('index/clearcache', 'App\Admin\Controller\IndexController::clearcache');
    Router::get('common/logout', 'App\Admin\Controller\CommonController::logout');
    Router::get('common/clear', 'App\Admin\Controller\CommonController::clear');
    Router::get('admin/index', 'App\Admin\Controller\AdminController::index');
    Router::get('admin/add', 'App\Admin\Controller\AdminController::add');
    Router::post('admin/addPost', 'App\Admin\Controller\AdminController::addPost');
    Router::get('admin/edit', 'App\Admin\Controller\AdminController::edit');
    Router::post('admin/editPost', 'App\Admin\Controller\AdminController::editPost');
    Router::post('admin/del', 'App\Admin\Controller\AdminController::del');
    Router::post('admin/enabling', 'App\Admin\Controller\AdminController::enabling');
    Router::get('role/index', 'App\Admin\Controller\RoleController::index');
    Router::get('role/add', 'App\Admin\Controller\RoleController::add');
    Router::post('role/addPost', 'App\Admin\Controller\RoleController::addPost');
    Router::get('role/edit', 'App\Admin\Controller\RoleController::edit');
    Router::post('role/editPost', 'App\Admin\Controller\RoleController::editPost');
    Router::post('role/del', 'App\Admin\Controller\RoleController::del');
    Router::post('role/enabling', 'App\Admin\Controller\RoleController::enabling');
    Router::post('role/authorizePost', 'App\Admin\Controller\RoleController::authorizePost');
    Router::get('role/authorize', 'App\Admin\Controller\RoleController::authorize');
    Router::get('node/index', 'App\Admin\Controller\NodeController::index');
    Router::get('node/add', 'App\Admin\Controller\NodeController::add');
    Router::post('node/addPost', 'App\Admin\Controller\NodeController::addPost');
    Router::get('node/edit', 'App\Admin\Controller\NodeController::edit');
    Router::post('node/editPost', 'App\Admin\Controller\NodeController::editPost');
    Router::post('node/del', 'App\Admin\Controller\NodeController::del');
    Router::post('node/enabling', 'App\Admin\Controller\NodeController::enabling');
    Router::post('node/changeSort', 'App\Admin\Controller\NodeController::changeSort');
    Router::get('node/unicode', 'App\Admin\Controller\NodeController::unicode');
},['middleware' => [CheckLogin::class]]);