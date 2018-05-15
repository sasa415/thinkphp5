<?php
namespace app\admin\model;
use think\Db;
use think\Model;
/**
 *  体测模型
 *  作者：马大锤
 */
class Goods extends Model
{
    protected $table = 'think_goods';//表名
    //增加
    function insertData($data)
    {
        return Db::table($this->table)->insertGetId($data);
    }
    //展示
    function show()
    {
        return Db::table($this->table)->select();
    }
    //删除
    function deleteData($id)
    {
        return Db::table($this->table)->where('u_id','=',$id)->delete();
    }
    //查询单条
    function findData($id)
    {
        return Db::table($this->table)->where('u_id','=',$id)->find();
    }
    //修改
    function updateData($data,$id)
    {
        return Db::table($this->table)->where('u_id','=',$id)->update($data);
    }
}