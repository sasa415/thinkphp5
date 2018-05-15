<?php
namespace app\index\controller;
use think\controller;
class Index extends controller
{
    //首页
    public function index()
    {
        return $this->fetch();
    }
}
