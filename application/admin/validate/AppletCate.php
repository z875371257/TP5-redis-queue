<?php
/**
 * 后台角色验证类
 */

namespace app\admin\validate;

class AppletCate extends Admin
{
    protected $rule = [
        'name|栏目名称' => 'require',
    ];

    protected $scene = [
        'add'  => ['name'],
        'edit' => ['name'],
    ];
}