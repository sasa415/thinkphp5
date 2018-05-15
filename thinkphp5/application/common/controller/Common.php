<?php
namespace app\common\controller;
use think\Controller;
use think\Session;
class Common extends Controller
{
    public function _initialize()
    {
        //判断是否登录
         $isLogin = Session::get('user.username','admin');
        //判断是否登录
        if (is_null($isLogin)) {
             $this->redirect('user/login');
        }else{
            return true;
        }
    }
    /**
     * @param $name
     * 如果在本控制器中找不到该操作那就运行我
     */
    public function _empty($name)
    {
        echo $name.'这个操作不存在';
    }

}
