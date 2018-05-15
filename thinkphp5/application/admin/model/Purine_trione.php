<?php

namespace app\admin\model;

use think\Model;
use think\Request;
use think\Db;
use think\paginate;
/**
 *  模型
 *  作者：
 */
class Purine_trione extends Model
{
	 public function add($data){
	 	$use = new purine_trione;
	 	// 执行添加
	 	$use->data($data)->save();
	 	// 添加成功返回记录id
	 	return $use->id;
	 }

	 public function ulist(){
	 	$use = new purine_trione;
	 	$data = Request::instance()->param();
	 	 $tmp = [];
	 	if (!empty($data['create_time']) ) {
	 		$data['create_time'] = strtotime($data['create_time']);
	 		$tmp['create_time'] = ['>',$data['create_time']];
	 	}else{
	 		$tmp['id'] = ['>',0];
	 	}
		// 判断有没有帅选条件 暂时没用
	 	// $tmp = [];
	 	// foreach ($data as $k => $v) {
	 	// 	if ($data[$k] != "" ) {
	 	// 		$tmp[$k] = $v;
	 	// 	}

	 	// }
	 	// 查询数据集
		$list = $use->where($tmp)->order('id desc')->paginate(5,false,[
				'query' => Request::instance()->param(),//不丢失已存在的url参数
			]);
		return $list;
	 }

	 public function mupdate($data){
	 	$use = new purine_trione;
	 	// 查询要改的数据
	 	$res = $use->where('id',$data['id'])->find();
	 	return $res->toArray();
	 }

	public function msave($data){
	   	$use = new purine_trione;
	   	// 执行修改
	   	$use->save($data,['id' => $data['id']]);
	   	// 添加成功返回记录id
	   	return $use->id;
	}

	public function mdelete($data){
	   	$use = new purine_trione;
	   	// 执行添加
	   	$res = $use->where('id' ,'=', $data['id'])->delete();
	   	//删除成功返回记录id
	   	if ($res) {
	   		return 1;
	   	}
	}

}
