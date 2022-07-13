<?php
namespace App\Admin\Controller;

use Hyperf\DbConnection\Db;
use Hyperf\Utils\Context;
use App\Model\MenuNode;

class IndexController extends BaseController{
    public function index(){
        $adminInfo = Context::get('adminInfo');
        $menuNodeModel = new MenuNode();
        $nodes = $menuNodeModel->roleMenu($adminInfo['role_id']);
//        var_dump($adminInfo);
//        var_dump($nodes);
        $nodes = getAdminMenu($nodes);

        return $this->view('', [
            'adminInfo'=>$adminInfo,
            'nodes'=>$nodes
        ]);
    }

    public function welcome(){
        $adminInfo = Context::get('adminInfo');

        return $this->view('', [
            'adminInfo'=>$adminInfo
        ]);
    }

}