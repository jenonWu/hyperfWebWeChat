<?php
declare(strict_types=1);
namespace App\Admin\Controller;

use App\Model\Admin;
use App\Model\Role;
use App\Admin\Validate\AdminValidate;

class AdminController extends BaseController
{

    public function index(){
        /**搜索条件**/
        $username = $this->request->input('username', '');

        $admins = Admin::where(function ($query) use ($username) {
            if (!empty($username)) {
                $query->where('username', 'like', "%$username%");
            }
        })
            ->orderBy("id", "DESC")
            ->get()->toArray();

        $roles = Role::get()->toArray();

        return $this->view('',[
            "username"=>$username,
            "roles"=>$roles,
            "admins"=>$admins
        ]);
    }

    public function add(){
        $roles = Role::get()->toArray();
        return $this->view('',[
            "roles"=>$roles
        ]);
    }


    public function addPost(AdminValidate $request){
        $data = $this->request->all();

        try {
            $request->validated();
        } catch (\Exception $e) {
            // 验证失败 输出错误信息
            echo "发送错误".$e->getError();
            return $this->error($e->getError());
        }

        unset($data['password_confirmation']);
        $salt = getStr(4);
        $data['password'] = adminPassword($data['password'], $salt);
        $data['password_salt'] = $salt;
        $data['create_time'] = time();
        $result             = Admin::insert($data);
        if ($result !== false) {
            return $this->success("添加成功！");
        } else {
            return $this->error("添加失败！");
        }
    }


    public function enabling(){
        $id = $this->request->input('id');
        $status = $this->request->input('status');
        if ($id == 1) {
            return $this->error("最高管理员不能修改！");
        }

        $result = Admin::where('id', $id)->update(['status'=>$status==0?1:0]);
        if ($result !== false) {
            return  $this->success("修改成功！");
        } else {
            return  $this->error("修改失败");
        }
    }

    public function edit(){
        $id = $this->request->input('id');
        $roles = Role::get()->toArray();
        $data = Admin::query()->find($id);
//        var_dump($data);
        return $this->view('',[
            "roles"=>$roles,
            "data"=>$data
        ]);
    }

    public function editPost(){
        $data = $this->request->all();
        // 从容器中获取
        $request = $this->container->get(AdminValidate::class);

        try {
            $request->scene('edit')->validated();
        } catch (Exception $e) {
            var_dump($e);
            return $this->error($e->getError());
        }

        $admin = Admin::query()->find($data['id']);

        //单独判断密码
        if(!empty($data['password']) || !empty($data['password_confirmation'])){
            if($data['password'] != $data['password_confirmation']) {
                return $this->error('两次密码不一致');
            }
            if(strlen($data['password'])<6 || strlen($data['password'])>16){
                return $this->error('密码必须6到16位');
            }

            unset($data['password_confirmation']);
            $salt = getStr(4);
            $data['password_salt'] = $salt;

            $admin->password = adminPassword($data['password'], $salt);
            $admin->password_salt = $salt;
        }

        $admin->username = $data['username'];
        $admin->role_id = $data['role_id'];

        $result = $admin->save();

        if ($result !== false) {
            return $this->success("修改成功！");
        } else {
            return $this->error("修改失败！");
        }
    }

    public function del(){
        $id = $this->request->input('id');
        if ($id == 1) {
            return $this->error("最高管理员不能删除！");
        }

        if (Admin::destroy($id) !== false) {
            return $this->success("删除成功！");
        } else {
            return $this->error("删除失败！");
        }
    }

}