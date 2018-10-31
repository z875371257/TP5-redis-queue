<?php
/**
 * 小程序栏目管理模型
 */

namespace app\admin\model;

class Overseas extends Admin
{
    protected $name = 'overseas';
    protected $autoWriteTimestamp = true;

    protected function getAdminStatusTextAttr($vaule, $data)
    {
        $text = [
            0 => '发送',
            1 => '已发送'
        ];
        return $text[$data['status']];
    }

    protected function getRegTimeAttr($value)
    {
        return $value>0?date('Y-m-d H:i:s',$value):'/';
    }
    
}
