<?php
namespace app\admin\controller;
/**
 *
 *
 * 导航栏  active参数 1 血脂 2 血糖 3 体测
 */
use app\common\controller\Common;
use think\Session;
use app\admin\model\BloodSuger;
use think\Hook;
use app\admin\model\UserForm;
use think\Db;
use think\Validate;
//命名需要修改
use app\admin\model\Purine_trione;
use app\admin\validate\Purine_trioneValidate;
use app\admin\model\Goods;
use think\Request;
class Index extends Common
{

    public function _initialize()
    {
        if(request()->action == 'index'){
            return true;
        }else{
            parent::_initialize();
        }
    }
    /**
     * 路由访问  http://localhost/thinkphp5/public/index.php/admin/index
     * @return [type] [description]
     */
    //首页
    public function index()
    {
        return $this->fetch('index',['username'=>Session::get('user.username','admin')]);
    }
    //血糖详细信息
    public function bloodSugar()
    {
        $this->assign('active',2);
        //获取
        $model = Db::name('blood_suger')->where('user_id' ,Session::get('user.id','admin'))->order('add_time desc')->paginate(5);
        return $this->fetch('blood_suger_index',['model'=>$model]);
    }
    //编辑新增  血糖信息
    public function editBloodSuger($id)
    {
        $blood = new BloodSuger();
        if (request()->isPost()){
            $data = input('post.');
            $validate = new Validate([
                ['blood_sampling','require|max:20','采血量不能为空|采血量不能超过20个字符'],
                ['test_range','max:20','test_range不能超过20个字符'],
                ['test_worm','max:20','测试温度 不能超过20个字符'],
                ['test_num','require|max:20','血糖值不能为空|血糖值不能超过20个字符'],
                ['test_eat','max:20','测试背景不能超过20个字符'],
                ['food','max:20','饮食不能超过20个字符'],
                ['glucose_tolerance','max:255','葡萄糖耐量不能超过255个字符'],
                ['evaluation','max:255','测试估测不能超过255个字符'],
                ['body','max:255','身体状况不能超过255个字符'],
            ]);
            //验证
             if (!$validate->check($data)) {
                $message = $validate->getError();
                $return = ['code'=>0,'message'=>$message];
               return json($return);
            }
            //新增
            if($id == 0){
                Db::startTrans();
                $data['user_id'] = Session::get('user.id','admin');
                $data['add_time'] = date("Y-m-d H:i:s" ,time());
                $res = BloodSuger::insert($data);
            //编辑
            }else{
                $data['edit_time'] = date("Y-m-d H:i:s" ,time());
                $res = BloodSuger::where('id', $id)->update($data);
            }
            if($res){
                 Db::commit();
                 return json(['code'=>200,'url'=>url('index/bloodSugar')]);
             }else{
                 Db::rollback();
                  return json(['code'=>0,'message'=>"保存有误"]);
             }
        }
        $this->assign('active',2);
        if($id == 0 || !isset($id)){
            return $this->fetch('blood_suger_add');
        }else{
             $model = BloodSuger::get($id);
            return $this->fetch('blood_suger_edit',['model'=>$model]);
        }
    }
    //删除血糖信息  单挑批量删除
    public function deleteBloodSuger()
    {
         if(request()->isPost()){
                $data = input('id/a');
                if(empty($data)){
                    return $this->error("请选择要删除的信息！",url('index/bloodSugar'));
                }
                BloodSuger::destroy($data);
                $this->success('删除成功',url('index/bloodSugar'));
         }else{
                 $this->error('获取失败');
         }
    }
    // 加载首页
    public function bloodFatIndex()
    {
         $this->assign('active',1);
        return $this->fetch('blood_fat_index');
    }
     // 执行添加健康信息
    public function add(){
        // 接收form表单传过来的数据
        $data = input('post.');
        $data['user_id'] = Session::get('user.id','admin');
        $validate = new Purine_trioneValidate();
        // 验证数据
        if(!$validate->check($data)){
        $this->error('填写完成信息', 'admin/bloodFatIndex');
         }
        // 调用model类 添加数据
        $Purine = new Purine_trione();
        // 返回是否成功
        $res = $Purine->add($data);
        //返回状态
        if ($res) {
                $res = array('code' =>1,'msg'=>'添加成功' );
        }else{
                $res = array('code' =>0,'msg'=>'添加失败' );
        }
        return $res;
    }

    // 列表页
    public function ulist(){
        $data = input('post.');
        // dump($data);
        // 调用model类 添加数据
        $Purine = new Purine_trione();
        // 返回是列表数据
        $list = $Purine->ulist();
        //返回数据
        $this->assign('list',$list);
        //加载模版
        $this->assign('active',1);
        return $this->fetch('blood_fat_ulist');
    }
    //
    public function update(){
        $data = input('get.');
        // 调用model类
        $Purine = new Purine_trione();
        $res = $Purine->mupdate($data);
        // 返回是要修改的数据
        $this->assign('active',1);
        return $this->fetch('blood_fat_update');
    }
    //
    public function save(){
        // 接收form表单传过来的数据
        $data = input('post.');
        // 调用model类 添加数据
        $Purine = new Purine_trione();
        // 返回是否成功
        $res = $Purine->msave($data);
        //返回状态
        if ($res) {
                $res = array('code' =>1,'msg'=>'修改成功' );
        }else{
                $res = array('code' =>0,'msg'=>'修改失败' );
        }
        return $res;
    }
    //删除
    public function delete(){
            // 接收form表单传过来的数据
            $data = input('post.');
            // 调用model类
            $Purine = new Purine_trione();
            // 返回是否成功0
            $res = $Purine->mdelete($data);
            //返回状态
        if ($res) {
                $res = array('code' =>1,'msg'=>'删除成功' );
        }else{
                $res = array('code' =>0,'msg'=>'删除失败' );
        }
        return $res;
    }
    //体测 新增
    public function physicalInsert()
    {
        if(request()->isPost()){
             $request = Request::instance();
            $data = $request->post();
            $goods = new Goods;
            $result = $goods->insertData($data);
            if ($result) {
                $this->success('新增成功', 'admin/Index/physicalShow');
            } else {
                $this->error('新增失败');
            }
        }else{
             $this->assign('active',3);
            return $this->fetch('physical_goods');

        }

    }
    //体测 展示
    public function physicalShow()
    {

        $goods = new Goods;
        $arr = $goods->show();
        $this->assign('active',3);
        return $this->fetch('physical_show',['arr' => $arr]);
    }
    //体测 删除
    public function physicalDelete()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $goods = new Goods;
        $result = $goods->deleteData($id);
        if ($result) {
            $this->success('删除成功', 'index/physicalShow');
        } else {
            $this->error('删除失败');
        }
    }
    //体测 修改页面
    public function physicalUpdate()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $goods = new Goods;
        $res = $goods->findData($id);
        $this->assign('active',3);
        return view('physical_update',['res' =>$res]);
    }
    //体测  修改数据
    public function physicalSave()
    {
        $id = $_POST['u_id'];
        $request = Request::instance();
        $data = $request->post();
        $goods = new Goods;
        $result = $goods->updateData($data,$id);
        if ($result) {
            $this->success('修改成功', 'admin/index/physicalShow');
        } else {
            $this->error('修改失败');
        }
    }

}
