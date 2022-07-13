<?php
declare(strict_types=1);
namespace App\Admin\Controller;


use App\Model\Role;
use App\Model\MenuNode;
use App\Model\RoleNode;

/**
 * 角色管理
 */
class RoleController extends BaseController
{

    public function index(){
        /**搜索条件**/
        $roles = Role::get()->toArray();

        return $this->view('',[
            "roles"=>$roles
        ]);
    }

    public function add(){
        return $this->view();
    }


    public function addPost(){
        $data = $this->request->all();

        if(empty($data['name'])){
            $this->error('请输入角色名称');
        }

        $data['create_time'] = time();
        $result             = Role::insert($data);
        if ($result !== false) {
            return $this->success("添加成功！", url("role/index"));
        } else {
            return $this->error("添加失败！");
        }

    }


    public function enabling(){
        $id = $this->request->input('id');
        $status = $this->request->input('status');
        if ($id == 1) {
            return $this->error("最高管理员角色不能修改！");
        }

        $result = Role::where('id', $id)->update(['status'=>$status==0?1:0]);
        if ($result !== false) {
            return  $this->success("修改成功！");
        } else {
            return  $this->error("修改失败");
        }
    }

    public function edit(){
        $id = $this->request->input('id');
        $data = Role::query()->find($id);
        if(empty($data)){
            $this->error("角色不存在");
        }
        return $this->view('',[
            "data"=>$data
        ]);
    }

    public function editPost(){
        $data = $this->request->all();
        if ($data['id'] == 1 ) {
            return $this->error("超级管理员角色不能修改！");
        }

        if (empty($data['name'])) {
            return $this->error('请输入角色名称');
        }
        $role = Role::query()->find($data['id']);
        $role->update_time = time();
        $role->name = $data['name'];
        $role->remark = $data['remark'];
        $role->status = $data['status'];
        $result = $role->save();

        if ($result !== false) {
            return $this->success("修改成功！");
        } else {
            return $this->error("修改失败！");
        }
    }

    public function del(){
        $id = $this->request->input('id');
        if ($id == 1) {
            return $this->error("超级管理员角色不能删除！！");
        }

        if (Role::destroy($id) !== false) {
            return $this->success("删除成功！");
        } else {
            return $this->error("删除失败！");
        }
    }

    //权限设置
    public function authorize(){
        $id = $this->request->input('id', 0, 'intval');

        $menuNodeModel = new MenuNode();
        $nodes = $menuNodeModel->getAuthorize($id);

        $nodes = sortNode($nodes);

        return $this->view('', [
            'role_id'=>$id,
            'nodes'=>$nodes,
        ]);
    }

    //提交设置
    public function authorizePost(){
        $role_id = $this->request->input('role_id', 0, 'intval');
        $nodes = $this->request->input('nodes');

        if(empty($role_id)){
            return $this->error("缺少role_id");
        }
        if(empty($nodes)){
            return $this->error("缺少nodes");
        }
        $nodes = explode(',', $nodes);
        if(empty($nodes)){
            return $this->error("缺少nodes");
        }

        RoleNode::where('role_id', $role_id)->delete();

        $insertData = [];
        foreach($nodes as $v){
            $insert=[];
            $insert['role_id']=$role_id;
            $insert['node_id']=$v;
            $insertData[] = $insert;
        }
        $res = RoleNode::insert($insertData);
        if ($res) {
            return $this->success("保存成功！");
        } else {
            return $this->error("保存失败！");
        }

    }
}