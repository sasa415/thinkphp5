<?php
/**
 *  作者： SaSa
 *  注释：json返回数据  错误code = 0 ，message 字段返回错误 用来表单验证 ，code = 1 直接message返回错误  code=200 成功 code= 2 最后操作失败
 */
namespace app\admin\controller;
use app\common\controller\Common;
use think\Validate;
use app\admin\model\UserForm;
use think\Hook;
use think\Cookie;
use think\Session;
class User extends Common
{
     public $timeout = 3600;
     public function _initialize()
    {
        if(request()->action == 'regist' || request()->action == 'login'){
            return true;
        }

    }
    //用户注册表
    public function regist()
    {

        //判断是否post提交
        if(request()->isPost()){
            //获取数据
            $data = input('post.');
            //验证规则
            $validate = new Validate([
                ['username','require|max:10|unique:user','名称不能为空|名称最多不能超过10个字符|用户名已存在'],
                ['password','require|confirm|^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$','密码不能为空|两次密码不一样|密码6到16位数字和字母组合'],
                ['phone','require|^1[34578]{1}\d{9}$|unique:user','手机号码必填|请输入正确的手机号码|此手机号已注册']
            ]);
            //验证
             if (!$validate->check($data)) {
                $message = $validate->getError();
                return json(['code'=>0,'message'=>$message]);
            }
            $user = new UserForm;
            // 过滤post数组中的非数据表字段数据
            $user->username     = $data['username'];
            $user->password    = md5($data['password']);
            $user->phone    = $data['phone'];
            $user->regist_time    = date("Y-m-d H:i:s" ,time());
            $res = $user->save();
            //参数返回
            if($res){
                return json(['code'=>200,'url'=> Url('user/login')]);
            }else{
                 return json(['code'=>2,'message'=>'注册失败。请联系客服']);
            }
        }
        //加载注册页
        return view('regist');

    }

    //用户登录
    public function login()
    {

        //判断是否post提交
        if(request()->isPost()){
             //获取数据
            $data = input('post.');
            $validate = new Validate([
                ['verifyCode','require|captcha','验证码不能为空|验证码有误'],
            ]);
            //验证
             if (!$validate->check($data)) {
                $message = $validate->getError();
                return json(['code'=>0,'message'=>$message]);
            }
            $password = md5($data['password']);
            //严格的判断
            $ret = model('UserForm')->get(['username'=>$data['username'],'password'=>$password]);
            if(!$ret){
                return json(['code'=>2,'message'=>'用户密码错误']);
            }
            //更新最后一次登录时间
            $up = model('UserForm')->updateById(['login_time'=>date("Y-m-d H:i:s" ,time())],$ret->id);

            //保存用户信息 用户信息 作用域
            session('user',$ret,'admin');
            return json(['code'=>200,'url'=> Url('index/index')]);
        }
        return view('login');
    }
    //退出登录
    public function logout()
    {
        //清楚session
        session(null,'admin');
        $this->redirect('user/login');

    }
}
