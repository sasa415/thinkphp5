<?php

namespace app\admin\Validate;
use think\Validate;

class Purine_trioneValidate extends Validate
{
	protected $rule = [
		'usea' =>'require',
		'creatinine' =>'require',
		'purine_trione' =>'require'
	];



}








 ?>