<?php


namespace app\index\controller;


use think\Controller;
use think\Request;
use app\index\model\Goods;


class Index extends Controller
{
    public function index()
    {
        return view('goods');
    }
    public function insert()
    {
        $request = Request::instance();
        $data = $request->post();
        $goods = new Goods;
        $result = $goods->insertData($data);
        if ($result) {
            $this->success('新增成功', 'index/Index/show');
        } else {
            $this->error('新增失败');
        }
        
    }
    //展示
    public function show()
    {
        $goods = new Goods;
        $arr = $goods->show();
        return $this->fetch('show',['arr' => $arr]);
    }
    //删除
    public function delete()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $goods = new Goods;
        $result = $goods->deleteData($id);
        if ($result) {
            $this->success('删除成功bbb', 'index/show');
        } else {
            $this->error('删除失败');
        }
    }
    //修改页面
    public function update()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $goods = new Goods;
        $res = $goods->findData($id);
        return view('update',['res' =>$res]);
    }
    //修改数据
    public function save()
    {
        $id = $_POST['u_id'];
        $request = Request::instance();
        $data = $request->post();
        $goods = new Goods;
        $result = $goods->updateData($data,$id);
        if ($result) {
            $this->success('修改成功bbb', 'index/Index/show');
        } else {
            $this->error('修改失败');
        }
    }
}