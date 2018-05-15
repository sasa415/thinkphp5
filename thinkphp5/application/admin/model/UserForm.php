<?php
namespace app\admin\model;
use think\Model;
use app\admin\model\BloodSuger;
/**
 *  用户信息模型
 *  作者：sasa
 */
class UserForm extends Model
{
    protected $pk = 'id';
    protected $table = 'think_user';
    protected $field = true;
    //利用主键  更新数据
    public function updateById($data,$id)
    {
        //过滤post数组中的数据  非数据表中的数据
        return $this->save($data,['id'=>$id]);

    }
    //建立和血糖的关联
    public function BloodSuger()
    {
        return $this->hasMany('BloodSuger','user_id');//hasMany是一对多
    }

}
