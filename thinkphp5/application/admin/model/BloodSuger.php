<?php
namespace app\admin\model;
use think\Model;
use app\admin\model\UserForm;
/**
 *  血糖模型
 *  作者：sasa
 */
class BloodSuger extends Model
{
    protected $pk = 'id';
    protected $table = 'think_blood_suger';
    protected $field = true;

    public function UserForm()
    {
        return $this->belongsTo('UserForm');
    }
}
