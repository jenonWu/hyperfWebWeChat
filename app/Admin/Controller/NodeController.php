<?php
declare(strict_types=1);
namespace App\Admin\Controller;

use App\Model\MenuNode;
use App\Admin\Validate\NodeValidate;

class NodeController extends BaseController
{

    public function index(){
        $nodes = MenuNode::query()->orderBy('sort','asc')->get();

        $nodes = sortNode($nodes);


        return $this->view('',[
            "nodes"=> $nodes
        ]);

    }

    public function add(){
        $pid = $this->request->input('id', 0);

        $nodes = MenuNode::query()->orderBy('sort','asc')->get()->toArray();

        $nodes = sortNode($nodes, 0, '|--');


        return $this->view('',[
            "pid"=>$pid,
            "nodes"=>$nodes,
        ]);

    }


    public function addPost(NodeValidate $request){
        $data = $this->request->all();

        try {
            $request->validated();
        } catch (\Exception $e) {
            // 验证失败 输出错误信息
            return $this->error($e->getError());
        }


        if($data['pid'] != 0){
            $PNode = MenuNode::select('level')->find($data['pid']);
            $data['level'] = $PNode['level']+1;
        }
        $result  = MenuNode::insert($data);
        if ($result !== false) {
            return $this->success("添加成功！", url("node/index"));
        } else {
            return $this->error("添加失败！");
        }
    }


    public function enabling(){

        $id = $this->request->input('id');
        $status = $this->request->input('status');

        $result = MenuNode::where('id', $id)->update(['status'=>$status]);
        if ($result !== false) {
            return  $this->success("修改成功！");
        } else {
            return  $this->error("修改失败");
        }
    }

    public function edit(){
        $id    = $this->request->input('id', 0, 'intval');
        $node = MenuNode::query()->find($id);
        var_dump($node);
        if(empty($node)){
            return $this->error("节点不存在");
        }

        $nodes = MenuNode::orderBy('sort', 'asc')->get()->toArray();
        $nodes = sortNode($nodes, 0, '|--');

        return $this->view('',[
            "nodes"=>$nodes,
            "data"=>$node
        ]);

    }

    public function editPost(NodeValidate $request){
        $data = $this->request->all();

        try {
            $request->validated();
        } catch (\Exception $e) {
            // 验证失败 输出错误信息
            return $this->error($e->getError());
        }

        if($data['pid'] != 0){
            $PNode = MenuNode::select('level')->find($data['pid']);
            $data['level'] = $PNode['level']+1;
        }


        $result = MenuNode::where('id', $data['id'])->update($data);
        if ($result !== false) {
            return $this->success("保存成功！");
        } else {
            return $this->error("未修改任何信息");
        }

    }


    //修改排序
    public function changeSort(){
        $id = $this->request->input('id', 0, 'intval');
        $sort = $this->request->input('sort', 0, 'intval');
        $result = MenuNode::where('id', $id)->update(['sort'=>$sort]);
        if ($result !== false) {
            return $this->success("修改成功！");
        } else {
            return $this->error("修改失败");
        }
    }

    public function del(){
        $id = $this->request->input('id', 0, 'intval');

        $res = MenuNode::where('pid', $id)->first();
        if($res){
            return $this->error("该节点下还有子节点，无法删除！");
        }

        if (MenuNode::destroy($id) !== false) {
            return $this->success("删除成功！");
        } else {
            return $this->error("删除失败！");
        }

    }

    public function unicode(){
        return $this->view();
    }

}