<?php
/**
 * 小程序栏目管理模型
 */

namespace app\admin\model;

class AppletCates extends Admin
{
    protected $name = 'applet_cates';
    protected $autoWriteTimestamp = true;

    protected function getAdminStatusTextAttr($vaule, $data)
    {
        $text = [
            0 => '禁用',
            1 => '正常'
        ];
        return $text[$data['status']];
    }

    protected function getRegTimeAttr($value)
    {
        return $value>0?date('Y-m-d H:i:s',$value):'/';
    }
    
}
